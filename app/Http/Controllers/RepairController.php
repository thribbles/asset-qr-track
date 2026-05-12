<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Asset;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with(['asset', 'requester'])
            ->orderBy('requested_at', 'desc')
            ->paginate(20);
        return view('repairs.index', compact('repairs'));
    }

    public function create()
    {
        $assets = Asset::where('status', 'damaged')->get();
        return view('repairs.create', compact('assets'));
    }

    public function createForAsset(Asset $asset)
    {
        return view('repairs.create', compact('asset'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'issue_description' => 'required|string',
            'requested_at' => 'required|date',
        ]);

        $validated['requested_by'] = auth()->id();
        $validated['status'] = 'pending';

        $repair = Repair::create($validated);

        AuditLog::log('create', 'repairs', $repair->id, null, $repair->toArray());

        return redirect()->route('assets.show', $validated['asset_id'])
            ->with('success', 'ส่งซ่อมครุภัณฑ์เรียบร้อย');
    }

    public function show(Repair $repair)
    {
        $repair->load(['asset', 'requester']);
        return view('repairs.show', compact('repair'));
    }

    public function edit(Repair $repair)
    {
        $repair->load(['asset', 'requester']);
        return view('repairs.edit', compact('repair'));
    }

    public function update(Request $request, Repair $repair)
    {
        $oldData = $repair->toArray();

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'repair_details' => 'nullable|string',
            'cost' => 'nullable|numeric',
            'repaired_by' => 'nullable|string',
            'completed_at' => 'nullable|date',
        ]);

        if ($validated['status'] === 'completed' && empty($validated['completed_at'])) {
            $validated['completed_at'] = now();
        }

        $repair->update($validated);

        AuditLog::log('update', 'repairs', $repair->id, $oldData, $repair->toArray());

        return redirect()->route('assets.show', $repair->asset_id)
            ->with('success', 'อัปเดตสถานะการซ่อมเรียบร้อย');
    }
}
