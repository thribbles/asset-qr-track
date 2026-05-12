<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\Asset;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index()
    {
        $inspections = Inspection::with(['asset', 'inspector'])
            ->orderBy('inspected_at', 'desc')
            ->paginate(20);
        return view('inspections.index', compact('inspections'));
    }

    public function create()
    {
        $assets = Asset::where('status', '!=', 'disposed')->get();
        return view('inspections.create', compact('assets'));
    }

    public function createForAsset(Asset $asset)
    {
        return view('inspections.create', compact('asset'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'inspected_at' => 'required|date',
            'condition_status' => 'required|in:good,fair,poor,damaged',
            'remarks' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $validated['inspected_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('inspections', 'public');
        }

        $inspection = Inspection::create($validated);

        // Update asset's latest inspection date
        $asset = Asset::find($validated['asset_id']);
        $asset->update(['latest_inspection_date' => $validated['inspected_at']]);

        AuditLog::log('create', 'inspections', $inspection->id, null, $inspection->toArray());

        // Redirect back to public page if coming from QR scan
        $asset = Asset::find($validated['asset_id']);
        if ($request->has('action')) {
            return redirect()->route('assets.public', $asset->qr_token)
                ->with('success', 'บันทึกการตรวจสอบเรียบร้อย');
        }

        return redirect()->route('assets.show', $validated['asset_id'])
            ->with('success', 'บันทึกการตรวจสอบเรียบร้อย');
    }

    public function show(Inspection $inspection)
    {
        $inspection->load(['asset', 'inspector']);
        return view('inspections.show', compact('inspection'));
    }

    public function edit(Inspection $inspection)
    {
        $inspection->load(['asset', 'inspector']);
        return view('inspections.edit', compact('inspection'));
    }

    public function update(Request $request, Inspection $inspection)
    {
        $oldData = $inspection->toArray();

        $validated = $request->validate([
            'condition_status' => 'required|in:good,fair,poor,damaged',
            'remarks' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $request->file('image')->store('inspections', 'public');
        }

        $inspection->update($validated);

        // Update asset's latest inspection date
        $asset = Asset::find($inspection->asset_id);
        $latestInspection = $asset->inspections()->latest('inspected_at')->first();
        if ($latestInspection) {
            $asset->update(['latest_inspection_date' => $latestInspection->inspected_at]);
        }

        AuditLog::log('update', 'inspections', $inspection->id, $oldData, $inspection->toArray());

        return redirect()->route('assets.show', $inspection->asset_id)
            ->with('success', 'อัปเดตการตรวจสอบเรียบร้อย');
    }
}
