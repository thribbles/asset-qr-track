@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-graph-up me-2"></i>Reports</h4>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-box display-1 text-primary mb-3"></i>
                <h5>Assets Report</h5>
                <p class="text-muted">View and export asset inventory</p>
                <a href="{{ route('reports.assets') }}" class="btn btn-primary">View Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-clipboard-check display-1 text-success mb-3"></i>
                <h5>Inspections Report</h5>
                <p class="text-muted">View inspection history</p>
                <a href="{{ route('reports.inspections') }}" class="btn btn-success">View Report</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-arrow-left-right display-1 text-info mb-3"></i>
                <h5>Transfers Report</h5>
                <p class="text-muted">View transfer history</p>
                <a href="{{ route('reports.transfers') }}" class="btn btn-info text-white">View Report</a>
            </div>
        </div>
    </div>
</div>
@endsection
