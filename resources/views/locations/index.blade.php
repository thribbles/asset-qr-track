@extends('layouts.app')

@section('title', 'Locations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-geo-alt me-2"></i>Locations</h4>
    <a href="{{ route('locations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add Location
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>อาคาร/สถานที่</th>
                    <th>ชั้น</th>
                    <th>ห้อง</th>
                    <th>รายละเอียด</th>
                    <th>Assets</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td>{{ $location->building }}</td>
                        <td>{{ $location->floor }}</td>
                        <td>{{ $location->room }}</td>
                        <td>{{ $location->detail }}</td>
                        <td><span class="badge bg-primary">{{ $location->assets_count }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('locations.destroy', $location) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this location?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No locations found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
