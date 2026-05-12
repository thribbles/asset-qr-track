<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        return view('public.home');
    }

    public function scan()
    {
        return view('public.scan');
    }

    public function showAsset(string $qr_token)
    {
        $asset = Asset::where('qr_token', $qr_token)
            ->with('location')
            ->firstOrFail();

        $oldData = $asset->toArray();

        // 1. Update latest inspection date on the asset
        $asset->update([
            'latest_inspection_date' => now()
        ]);

        // 2. Log the scan event
        \App\Models\AuditLog::log('qr_scan', 'assets', $asset->id, $oldData, $asset->toArray());

        // 3. If user is logged in, automatically create an inspection record if none today
        if (auth()->check()) {
            $hasInspectionToday = \App\Models\Inspection::where('asset_id', $asset->id)
                ->whereDate('inspected_at', today())
                ->exists();

            if (!$hasInspectionToday) {
                \App\Models\Inspection::create([
                    'asset_id' => $asset->id,
                    'inspected_by' => auth()->id(),
                    'inspected_at' => now(),
                    'condition_status' => 'good',
                    'remarks' => 'ตรวจสอบอัตโนมัติผ่านการสแกน QR Code',
                ]);
                session()->flash('success', 'บันทึกการตรวจสอบอัตโนมัติเรียบร้อย (สภาพดี)');
            }
        }

        return view('public.asset', compact('asset'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $assets = Asset::query()
            ->when($query, function ($q) use ($query) {
                $q->where('asset_code', 'like', "%{$query}%")
                  ->orWhere('asset_name', 'like', "%{$query}%");
            })
            ->with('location')
            ->where('status', '!=', 'disposed')
            ->limit(20)
            ->get();

        return view('public.search', compact('assets', 'query'));
    }

    public function manual()
    {
        return view('public.manual');
    }
}
