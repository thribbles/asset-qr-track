@extends('layouts.app')

@section('title', 'Inspections Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-clipboard-check me-2"></i>Inspections Report</h4>
    <div>
        <a href="{{ route('reports.export', ['type' => 'inspections', 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>PDF
        </a>
        <a href="{{ route('reports.export', ['type' => 'inspections', 'format' => 'csv']) }}" class="btn btn-success btn-sm">
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
                    <th>Inspector</th>
                    <th>Date</th>
                    <th>Condition</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inspections as $inspection)
                    <tr>
                        <td>{{ $inspection->asset->asset_name }}</td>
                        <td>{{ $inspection->inspector->name }}</td>
                        <td>{{ $inspection->inspected_at->format('M d, Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $inspection->condition_status === 'good' ? 'success' : ($inspection->condition_status === 'damaged' ? 'danger' : 'warning') }}">
                                {{ ucfirst($inspection->condition_status) }}
                            </span>
                        </td>
                        <td>{{ $inspection->remarks }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No inspections found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
