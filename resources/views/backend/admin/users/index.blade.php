@extends('layout.master')

@push('plugin-styles')
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/rowreorder/1.3.3/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endpush

@section('content')
    @php
        $route = explode('.', Route::currentRouteName());
    @endphp
    <div class="nav-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="{{ route($route[0] . '.index') }}">{{ ucfirst($route[0]) }}</a></li>
            <a href="{{ route('users.export') }}" class="btn btn-sm btn-success breadcrumb-button" style="background-color: #28a745; border-color: #28a745;">
                <i class="fa-solid fa-download"></i> Export CSV
            </a>
        </ol>
    </div>

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone..." 
                           value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-control">
                        <option value="">All Roles</option>
                        <option value="user" {{ ($role ?? '') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ ($role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="superadmin" {{ ($role ?? '') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="restricted" {{ ($role ?? '') == 'restricted' ? 'selected' : '' }}>Restricted</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff;">Filter</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 style="font-weight: bold;">Users Management</h4>
            <p class="text-muted mb-0">Total Users: {{ $data->total() }} | Showing {{ $data->count() }} results</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id='responsive-datatables'>
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>User Info</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Wallet Balance</th>
                            <th>Active Plans</th>
                            <th>Signup Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $ser = 0;
                        @endphp
                        @foreach ($data as $key => $item)
                            @if (Auth::user()['role'] != 'SUPERADMIN' && in_array($item['role'], ['SUPERADMIN', 'ADMIN']))
                                @continue
                            @endif
                            @php
                                $bg = $item->trashed() ? 'bg-secondary' : '';
                                $activePlans = $item->subscriptions->where('expire_at', '>', date('Y-m-d'))->count();
                                $totalEarnings = $item->subscriptions->sum('earning');
                            @endphp
                            <tr>
                                <td class="{{ $bg }}">{{ ++$ser }}</td>
                                <td class="{{ $bg }}">
                                    <div>
                                        <strong>{{ $item['name'] }}</strong><br>
                                        <small class="text-muted">{{ $item['email'] }}</small><br>
                                        <small class="text-info">Referred by: {{ $item['referred_by'] ? $referrals[$item['referred_by']] : 'Direct' }}</small>
                                    </div>
                                </td>
                                <td class="{{ $bg }}">
                                    {{ $item['contact'] ?? 'N/A' }}
                                </td>
                                <td class="{{ $bg }}">
                                    @if($item->trashed())
                                        <span class="badge badge-danger" style="background-color: #dc3545;">Inactive</span>
                                    @else
                                        <span class="badge badge-{{ $item['role'] == 'user' ? 'success' : ($item['role'] == 'admin' ? 'warning' : 'info') }}"
                                              style="background-color: {{ $item['role'] == 'user' ? '#28a745' : ($item['role'] == 'admin' ? '#ffc107' : '#007bff') }};">
                                            {{ ucfirst($item['role']) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="{{ $bg }}">
                                    <div>
                                        <strong>₨{{ number_format($item['userAccount']['net_amount'] ?? 0) }}</strong><br>
                                        <small class="text-muted">Earnings: ₨{{ number_format($totalEarnings) }}</small>
                                    </div>
                                </td>
                                <td class="{{ $bg }}">
                                    <span class="badge badge-primary" style="background-color: #007bff;">{{ $activePlans }}</span>
                                </td>
                                <td class="{{ $bg }}">
                                    {{ $item['created_at']->format('M d, Y') }}<br>
                                    <small class="text-muted">{{ $item['created_at']->diffForHumans() }}</small>
                                </td>
                                <td class="{{ $bg }}">
                                    @if ($item['role'] == 'RESTRICTED')
                                        <form method="POST" action="{{ route($route[0] . '.update', $item['id']) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="role" value="approve" class="btn btn-success btn-sm" style="background-color: #28a745; border-color: #28a745;">
                                                <i class='fa-regular fa-circle-check'></i> Approve
                                            </button>
                                        </form>
                                    @else
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $item['id']) }}" class="btn btn-info btn-sm" style="background-color: #007bff; border-color: #007bff;">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            @if($item->trashed())
                                                <form method="POST" action="{{ route($route[0] . '.update', $item['id']) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="status" value="activate" class="btn btn-success btn-sm" style="background-color: #28a745; border-color: #28a745;">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route($route[0] . '.update', $item['id']) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="status" value="deactivate" class="btn btn-warning btn-sm" style="background-color: #ffc107; border-color: #ffc107;">
                                                        <i class="fa-solid fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if(Auth::user()['role'] == 'SUPERADMIN')
                                                <a href="{{route('adjustUserAccount',$item['id'])}}" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $data->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.3.3/js/dataTables.rowReorder.min.js">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js">
    </script>
    {!! Html::script('/assets/js/dashboard.js') !!}

    <script>
        $(document).ready(function() {
            var table = $('#responsive-datatables').DataTable({
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true,
                pageLength: 25,
                order: [[6, 'desc']] // Sort by signup date
            });
        });
    </script>
@endpush
