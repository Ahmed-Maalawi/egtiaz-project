<x-dashboard.main-layout>
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
        }

        .dashboard-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-left: 0.25rem solid #e3e6f0;
            border-radius: 0.5rem;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .dashboard-card.border-left-primary {
            border-left-color: #4e73df !important;
        }

        .dashboard-card.border-left-success {
            border-left-color: #1cc88a !important;
        }

        .dashboard-card.border-left-info {
            border-left-color: #36b9cc !important;
        }

        .dashboard-card.border-left-warning {
            border-left-color: #f6c23e !important;
        }

        .dashboard-card.border-left-danger {
            border-left-color: #e74a3b !important;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .bg-primary { background-color: #4e73df !important; }
        .bg-success { background-color: #1cc88a !important; }
        .bg-info { background-color: #36b9cc !important; }
        .bg-warning { background-color: #f6c23e !important; }
        .bg-danger { background-color: #e74a3b !important; }

        .text-xs {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1rem;
        }

        .page-title {
            color: #5a5c69;
            font-weight: 400;
            margin-bottom: 2rem;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: #5a5c69;
        }

        .metric-change {
            font-size: 0.875rem;
        }

        .section-title {
            color: #5a5c69;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e3e6f0;
            padding-bottom: 0.5rem;
        }
    </style>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Dashboard') }}</h1>
        <div class="text-muted">
            <i class="fas fa-calendar-alt"></i>
            {{ now()->format('F j, Y') }}
        </div>
    </div>

    <!-- User & Employee Metrics -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="section-title">{{ __('User & Employee Overview') }}</h4>
        </div>

        @role('super-admin|admin')
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-primary mb-1">{{ __('Total Users') }}</div>
                                <div class="metric-value">
                                    {{ $data['total_users'] ?? '0' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endrole
        @can('employees')
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-success mb-1">{{ __('Total Employees') }}</div>
                                <div class="metric-value">
                                    {{ $data['total_employee'] ?? '0' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon bg-success">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('stages')
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-info mb-1">{{ __('Total Stages') }}</div>
                                <div class="metric-value">
                                    {{ $data['total_stages'] ?? '0' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon bg-info">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-warning mb-1">{{ __('Employee Stagings') }}</div>
                                <div class="metric-value">
                                    {{ $data['total_employee_stages'] ?? '0' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon bg-warning">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    <!-- Leave Management Metrics -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="section-title">{{ __('Leave Management') }}</h4>
        </div>
        @can('leaves')
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-info mb-1">{{ __('Total Leaves') }}</div>
                                <div class="metric-value">
                                    {{ $data['total_leaves'] ?? '0' }}
                                </div>
                                <div class="mt-2">
                                    <small class="text-info me-2">
                                        <i class="fas fa-calendar"></i> This month
                                    </small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon bg-info">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-success mb-1">{{ __('Leaves Approved') }}</div>
                                <div class="metric-value">
                                    {{ $data['total_leaves_approved'] ?? '0' }}
                                </div>
                                <div class="mt-2">
                                    <small class="text-success me-2">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon bg-success">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @hasrole('super-admin|admin')
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-warning mb-1">{{ __('Total Stages Profit') }}</div>
                                <div class="metric-value">
                                    {{ $data['total_profit'] ?? '0' }}
                                </div>
                                <div class="mt-2">
                                    <small class="text-warning me-2">
                                        <i class="fas fa-dollar"></i>
                                    </small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon bg-warning">
                                    <i class="fas fa-dollar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endhasrole

        @can('eos')
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card border-left-danger h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-danger mb-1">{{ __('End of Service') }}</div>
                                <div class="metric-value">
                                    {{ $data['total_eos'] ?? '0' }}
                                </div>
                                <div class="mt-2">
                                    <small class="text-danger me-2">
                                        <i class="fas fa-user-times"></i> Total EOS
                                    </small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="stats-icon bg-danger">
                                    <i class="fas fa-user-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    <!-- Quick Actions -->
    @canany(['employees','leaves','states','reports'])
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="section-title">{{ __('Quick Actions') }}</h4>
            </div>

            @can('employees')
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card dashboard-card border-left-primary h-100">
                        <div class="card-body text-center py-4">
                            <div class="stats-icon bg-primary mx-auto mb-3">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h6 class="card-title">{{ __('Add Employee') }}</h6>
                            <a href="{{ route('admins.employees.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('Add New') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('leaves')
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card dashboard-card border-left-success h-100">
                        <div class="card-body text-center py-4">
                            <div class="stats-icon bg-success mx-auto mb-3">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <h6 class="card-title">{{ __('Manage Leaves') }}</h6>
                            <a href="{{ route('admins.hr.leaves.index') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-calendar"></i> {{ __('View Leaves') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('stages')
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card dashboard-card border-left-info h-100">
                        <div class="card-body text-center py-4">
                            <div class="stats-icon bg-info mx-auto mb-3">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h6 class="card-title">{{ __('Manage Stages') }}</h6>
                            <a href="{{ route('admins.stages.index') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-cog"></i> {{ __('Configure') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('reports')
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card dashboard-card border-left-warning h-100">
                        <div class="card-body text-center py-4">
                            <div class="stats-icon bg-warning mx-auto mb-3">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h6 class="card-title">{{ __('View Reports') }}</h6>
                            <a href="{{ route('admins.stages.index') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-chart-line"></i> {{ __('Reports') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    @endcanany

    <!-- Latest Leaves and EOS -->
   @can(['leaves', 'eos'])
        <div class="row mt-4">
            @can('leaves')
                <!-- Latest Leaves -->
                <div class="col-lg-6 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-success">{{ __('Latest Leaves') }}</h6>
                            <a href="{{ route('admins.hr.leaves.index') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-eye"></i> {{ __('View All') }}
                            </a>
                        </div>
                        <div class="card-body">
                            @if(isset($data['latest_leaves']) && count($data['latest_leaves']) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                        <tr>
                                            <th>{{ __('Employee') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('From') }}</th>
                                            <th>{{ __('To') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['latest_leaves'] as $leave)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <div class="fw-bold">{{ $leave->user->name ?? 'N/A' }}</div>
                                                            <small class="text-muted">{{ $leave->user->user_id ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info text-white">{{ $leave->type ?? 'N/A' }}</span>
                                                </td>
                                                <td>{{ $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') : 'N/A' }}</td>
                                                <td>{{ $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') : 'N/A' }}</td>
                                                <td class="text-white">
                                                    @if($leave->status == 'approved')
                                                        <span class="badge bg-success">{{ __('Approved') }}</span>
                                                    @elseif($leave->status == 'pending')
                                                        <span class="badge bg-warning">{{ __('Pending') }}</span>
                                                    @elseif($leave->status == 'rejected')
                                                        <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($leave->status ?? 'Unknown') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('No recent leaves found') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endcan
            @can('eos')
                <!-- Latest EOS -->
                <div class="col-lg-6 mb-4">
                    <div class="card dashboard-card">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-danger">{{ __('Latest End of Service') }}</h6>
                            <a href="{{ route('admins.eos.index')}}" class="btn btn-danger btn-sm">
                                <i class="fas fa-eye"></i> {{ __('View All') }}
                            </a>
                        </div>
                        <div class="card-body">
                            @if(isset($data['latest_eos']) && count($data['latest_eos']) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                        <tr>
                                            <th>{{ __('Employee') }}</th>
                                            <th>{{ __('Join Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
                                            <th>{{ __('Net Pay') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['latest_eos'] as $eos)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <div class="fw-bold">{{ $eos->user->name ?? 'N/A' }}</div>
                                                            <small class="text-muted">{{ $eos->user->user_id ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $eos->joining_date ? \Carbon\Carbon::parse($eos->joining_date)->format('M d, Y') : 'N/A' }}</td>
                                                <td>{{ $eos->leaving_date ? \Carbon\Carbon::parse($eos->leaving_date)->format('M d, Y') : 'N/A' }}</td>
                                                <td>{{ $eos->net_pay}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-user-times fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('No recent end of service records found') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    @endcan

    @can('stages')
        <!-- Latest Paid Stages -->
        <div class="row mt-4">
            <div class="col-12 mb-4">
                <div class="card dashboard-card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('Latest Paid Stages') }}</h6>
                        <a href="{{ route('admins.stages.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> {{ __('View All') }}
                        </a>
                    </div>
                    <div class="card-body">
                        @if(isset($data['latest_paid_stages']) && count($data['latest_paid_stages']) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Employee') }}</th>
                                        <th>{{ __('Stage') }}</th>
                                        <th>{{ __('Stage Type') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Payment Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['latest_paid_stages'] as $stage)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <div class="fw-bold">{{ $stage->employee?->name ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-white">{{ $stage->stage->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $stage->stage->iqamaType->getTranslation('name', app()->getLocale()) ?? 'N/A' }}</td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    {{ number_format($stage->stage->price ?? 0, 2) }} {{ __('SAR') }}
                                                </span>
                                            </td>
                                            <td>{{ $stage->completed_at ? \Carbon\Carbon::parse($stage->completed_at)->format('M d, Y') : 'N/A' }}</td>
                                            <td class="text-white">
                                                @if($stage->status == 'paid')
                                                    <span class="badge bg-success">{{ __('Paid') }}</span>
                                                @elseif($stage->status == 'pending')
                                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                                @elseif($stage->status == 'cancelled')
                                                    <span class="badge bg-danger">{{ __('Cancelled') }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ ucfirst($stage->status ?? 'Unknown') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('No recent paid stages found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endcan

</x-dashboard.main-layout>
