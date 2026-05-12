<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Inspection;
use App\Models\Transfer;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_assets' => Asset::count(),
            'active_assets' => Asset::where('status', 'active')->count(),
            'damaged_assets' => Asset::where('status', 'damaged')->count(),
            'borrowed_assets' => \App\Models\Loan::where('status', 'borrowed')->count(),
            'disposed_assets' => Asset::where('status', 'disposed')->count(),
        ];

        $recent_inspections = Inspection::with(['asset', 'inspector'])
            ->orderBy('inspected_at', 'desc')
            ->limit(5)
            ->get();

        $recent_transfers = Transfer::with(['asset', 'toLocation', 'transferredBy'])
            ->orderBy('transferred_at', 'desc')
            ->limit(5)
            ->get();

        $assets_by_type = Asset::selectRaw('asset_type, count(*) as count')
            ->groupBy('asset_type')
            ->pluck('count', 'asset_type');

        return view('dashboard', compact('stats', 'recent_inspections', 'recent_transfers', 'assets_by_type'));
    }
}
