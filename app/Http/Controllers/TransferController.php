<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Asset;
use App\Models\Location;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = Transfer::with(['asset', 'fromLocation', 'toLocation', 'transferredBy'])
            ->orderBy('transferred_at', 'desc')
            ->paginate(20);
        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        $assets = Asset::where('status', '!=', 'disposed')->get();
        $locations = Location::all();
        return view('transfers.create', compact('assets', 'locations'));
    }

    public function createForAsset(Asset $asset)
    {
        $locations = Location::all();
        return view('transfers.create', compact('asset', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'to_location' => 'required|exists:locations,id',
            'transferred_at' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $asset = Asset::find($validated['asset_id']);
        $fromLocationId = $asset->location_id;

        $validated['from_location'] = $fromLocationId;
        $validated['transferred_by'] = auth()->id();

        $transfer = Transfer::create($validated);

        // Update asset location
        $asset->update(['location_id' => $validated['to_location']]);

        AuditLog::log('create', 'transfers', $transfer->id, null, $transfer->toArray());

        return redirect()->route('assets.show', $validated['asset_id'])
            ->with('success', 'Transfer recorded successfully.');
    }

    public function show(Transfer $transfer)
    {
        $transfer->load(['asset', 'fromLocation', 'toLocation', 'transferredBy']);
        return view('transfers.show', compact('transfer'));
    }
}
