<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Inspection;
use App\Models\Transfer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function assets(Request $request)
    {
        $assets = Asset::with('location')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->get('status'));
            })
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('asset_type', $request->get('type'));
            })
            ->orderBy('asset_code')
            ->get();

        return view('reports.assets', compact('assets'));
    }

    public function inspections(Request $request)
    {
        $inspections = Inspection::with(['asset', 'inspector'])
            ->when($request->filled('from'), function ($q) use ($request) {
                $q->whereDate('inspected_at', '>=', $request->get('from'));
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->whereDate('inspected_at', '<=', $request->get('to'));
            })
            ->orderBy('inspected_at', 'desc')
            ->get();

        return view('reports.inspections', compact('inspections'));
    }

    public function transfers(Request $request)
    {
        $transfers = Transfer::with(['asset', 'fromLocation', 'toLocation', 'transferredBy'])
            ->when($request->filled('from'), function ($q) use ($request) {
                $q->whereDate('transferred_at', '>=', $request->get('from'));
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->whereDate('transferred_at', '<=', $request->get('to'));
            })
            ->orderBy('transferred_at', 'desc')
            ->get();

        return view('reports.transfers', compact('transfers'));
    }

    public function export(string $type, Request $request)
    {
        $format = $request->get('format', 'pdf');

        switch ($type) {
            case 'assets':
                $data = Asset::with('location')->orderBy('asset_code')->get();
                $view = 'reports.exports.assets';
                $filename = 'assets-report';
                break;
            case 'inspections':
                $data = Inspection::with(['asset', 'inspector'])->orderBy('inspected_at', 'desc')->get();
                $view = 'reports.exports.inspections';
                $filename = 'inspections-report';
                break;
            case 'transfers':
                $data = Transfer::with(['asset', 'fromLocation', 'toLocation', 'transferredBy'])->orderBy('transferred_at', 'desc')->get();
                $view = 'reports.exports.transfers';
                $filename = 'transfers-report';
                break;
            default:
                abort(404);
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView($view, ['data' => $data]);
            return $pdf->download($filename . '.pdf');
        }

        if ($format === 'csv') {
            return $this->exportCsv($data, $filename);
        }

        abort(400, 'Unsupported format');
    }

    private function exportCsv($data, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        return response()->stream(function () use ($data) {
            $handle = fopen('php://output', 'w');

            if ($data->isNotEmpty()) {
                fputcsv($handle, array_keys($data->first()->toArray()));

                foreach ($data as $item) {
                    fputcsv($handle, $item->toArray());
                }
            }

            fclose($handle);
        }, 200, $headers);
    }
}
