@extends('layouts.app')

@section('title', 'Transfers Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-arrow-left-right me-2"></i>Transfers Report</h4>
    <div>
        <a href="{{ route('reports.export', ['type' => 'transfers', 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>PDF
        </a>
        <a href="{{ route('reports.export', ['type' => 'transfers', 'format' => 'csv']) }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-excel me-1"></i>CSV
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Asset</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Date</th>
                    <th>Transferred By</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $transfer)
                    <tr>
                        <td>{{ $transfer->asset->asset_name }}</td>
                        <td>{{ $transfer->fromLocation->building ?? 'Unknown' }}</td>
                        <td>{{ $transfer->toLocation->building }}</td>
                        <td>{{ $transfer->transferred_at->format('M d, Y H:i') }}</td>
                        <td>{{ $transfer->transferredBy->name }}</td>
                        <td>{{ $transfer->reason }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No transfers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
