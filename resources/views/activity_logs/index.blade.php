@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Activity Log</h2>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('activity.logs') }}" class="mb-3">
        <div class="row">
            <div class="col-md-2">
                <input type="text" name="user" class="form-control" placeholder="Cari User" value="{{ request('user') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="role" class="form-control" placeholder="Cari Role" value="{{ request('role') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="activity" class="form-control" placeholder="Cari Aktivitas" value="{{ request('activity') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="model" class="form-control" placeholder="Cari Model" value="{{ request('model') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-sm w-100">Filter</button>
                <a href="{{ route('activity.logs') }}" class="btn btn-secondary btn-sm w-100 mt-1">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Aktivitas</th>
                    <th>Model</th>
                    <th>Detail</th>
                    <th>IP Address</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration + ($logs->currentPage()-1)*$logs->perPage() }}</td>
                    <td>{{ $log->user?->name ?? 'Guest' }}</td>
                    <td>
                        @php
                            $role = $log->user?->role ?? '-';
                            if ($role === 'resident') {
                                $role = 'admin';
                            }
                        @endphp
                        {{ $role }}
                    </td>
                    <td>
                        @if(str_contains(strtolower($log->activity), 'approved'))
                            <span class="badge badge-success">{{ $log->activity }}</span>
                        @elseif(str_contains(strtolower($log->activity), 'rejected'))
                            <span class="badge badge-danger">{{ $log->activity }}</span>
                        @elseif(str_contains(strtolower($log->activity), 'dikembalikan'))
                            <span class="badge badge-primary">{{ $log->activity }}</span>
                        @else
                            <span class="badge badge-secondary">{{ $log->activity }}</span>
                        @endif
                    </td>
                    <td>{{ class_basename($log->model) ?? '-' }}</td>
                    <td style="max-width:300px; word-wrap: break-word;">{{ $log->details ?? '-' }}</td>
                    <td>{{ $log->ip_address ?? '-' }}</td>
                    <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada aktivitas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $logs->withQueryString()->links() }}
    </div>
</div>
@endsection

<style>
/* Kecilkan tombol pagination */
.pagination .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
.pagination .page-item {
    margin: 0 2px;
}
.pagination .page-link svg {
    display: none;
}
</style>

