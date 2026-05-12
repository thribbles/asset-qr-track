@extends('layouts.app')

@section('title', 'Assets Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-box me-2"></i>Assets Report</h4>
    <div>
        <a href="{{ route('reports.export', ['type' => 'assets', 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>PDF
        </a>
        <a href="{{ route('reports.export', ['type' => 'assets', 'format' => 'csv']) }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-excel me-1"></i>CSV
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Asset Code</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $asset)
                    <tr>
                        <td><code>{{ $asset->asset_code }}</code></td>
                        <td>{{ $asset->asset_name }}</td>
                        <td>{{ $asset->asset_type === 'material' ? 'Material' : 'Durable' }}</td>
                        <td>{{ $asset->location->building ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $asset->status === 'active' ? 'success' : ($asset->status === 'damaged' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($asset->status) }}
                            </span>
                        </td>
                        <td>{{ $asset->purchase_date?->format('M d, Y') ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No assets found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
