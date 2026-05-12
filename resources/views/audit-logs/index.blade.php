@extends('layouts.app')

@section('title', 'บันทึกการใช้งาน (Audit Logs)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-journal-text me-2"></i>บันทึกการใช้งานระบบ</h4>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>วัน-เวลา</th>
                    <th>ผู้ใช้</th>
                    <th>การกระทำ</th>
                    <th>ส่วนของระบบ</th>
                    <th>รหัสอ้างอิง</th>
                    <th>รายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>
                            <span class="badge bg-{{ $log->action === 'login' ? 'info' : ($log->action === 'delete' ? 'danger' : ($log->action === 'update' ? 'warning text-dark' : 'success')) }} text-uppercase">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>{{ $log->table_name }}</td>
                        <td>{{ $log->record_id ?: '-' }}</td>
                        <td>
                            @if($log->old_data || $log->new_data)
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                                    <i class="bi bi-search"></i> ดูข้อมูล
                                </button>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">รายละเอียดการเปลี่ยนแปลง (ID: {{ $log->id }})</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="row">
                                                    @if($log->old_data)
                                                        <div class="col-md-6 border-end">
                                                            <h6>ข้อมูลเดิม (Old Data)</h6>
                                                            <pre class="bg-light p-2 rounded small" style="max-height: 400px; overflow-y: auto;">{{ json_encode($log->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if($log->new_data)
                                                        <div class="col-md-{{ $log->old_data ? '6' : '12' }}">
                                                            <h6>ข้อมูลใหม่ (New Data)</h6>
                                                            <pre class="bg-light p-2 rounded small" style="max-height: 400px; overflow-y: auto;">{{ json_encode($log->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">ไม่มีบันทึกการใช้งาน</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
