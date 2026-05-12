@extends('layouts.app')

@section('title', 'Transfers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-arrow-left-right me-2"></i>Transfers</h4>
    <a href="{{ route('transfers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>New Transfer
    </a>
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
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $transfer)
                    <tr>
                        <td>{{ $transfer->asset->asset_name }}</td>
                        <td>{{ $transfer->fromLocation->building ?? 'Unknown' }}</td>
                        <td>{{ $transfer->toLocation->building }}</td>
                        <td>{{ $transfer->transferred_at->format('M d, Y') }}</td>
                        <td>{{ $transfer->transferredBy->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No transfers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transfers->hasPages())
        <div class="card-footer">
            {{ $transfers->links() }}
        </div>
    @endif
</div>
@endsection
