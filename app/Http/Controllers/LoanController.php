<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Asset;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['asset', 'issuer', 'receiver'])
            ->orderBy('borrowed_at', 'desc')
            ->paginate(20);
        return view('loans.index', compact('loans'));
    }

    public function create(Request $request)
    {
        $selectedAsset = null;
        if ($request->has('asset_id')) {
            $selectedAsset = Asset::findOrFail($request->asset_id);
        }
        
        $assets = Asset::where('status', 'active')->get();
        return view('loans.create', compact('assets', 'selectedAsset'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_department' => 'nullable|string|max:255',
            'borrowed_at' => 'required|date',
            'due_at' => 'nullable|date|after_or_equal:borrowed_at',
            'purpose' => 'nullable|string',
            'borrow_remarks' => 'nullable|string',
        ]);

        $validated['issued_by'] = auth()->id();
        $validated['status'] = 'borrowed';

        $loan = Loan::create($validated);

        // Update asset status if needed, or just keep it active but track via loan
        // For this system, we'll keep it active but maybe add a note or indicator
        
        AuditLog::log('create', 'loans', $loan->id, null, $loan->toArray());

        return redirect()->route('loans.index')
            ->with('success', 'บันทึกการยืมเรียบร้อย');
    }

    public function show(Loan $loan)
    {
        $loan->load(['asset', 'issuer', 'receiver']);
        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        return view('loans.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        if ($request->has('action') && $request->action === 'return') {
            return $this->returnAsset($request, $loan);
        }

        $oldData = $loan->toArray();
        
        $validated = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'borrower_department' => 'nullable|string|max:255',
            'due_at' => 'nullable|date',
            'purpose' => 'nullable|string',
            'borrow_remarks' => 'nullable|string',
        ]);

        $loan->update($validated);
        
        AuditLog::log('update', 'loans', $loan->id, $oldData, $loan->toArray());

        return redirect()->route('loans.index')
            ->with('success', 'อัปเดตข้อมูลการยืมเรียบร้อย');
    }

    protected function returnAsset(Request $request, Loan $loan)
    {
        $oldData = $loan->toArray();

        $validated = $request->validate([
            'returned_at' => 'required|date',
            'return_remarks' => 'nullable|string',
            'status' => 'required|in:returned,damaged,lost',
        ]);

        $validated['received_by'] = auth()->id();
        
        $loan->update($validated);

        // If returned as damaged, update asset status
        if ($validated['status'] === 'damaged') {
            $loan->asset->update(['status' => 'damaged']);
        }

        AuditLog::log('return', 'loans', $loan->id, $oldData, $loan->toArray());

        return redirect()->route('loans.index')
            ->with('success', 'บันทึกการคืนเรียบร้อย');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')
            ->with('success', 'ลบข้อมูลการยืมเรียบร้อย');
    }
}
