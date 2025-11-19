<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}"
        style="margin-top: 30px;margin-bottom:20px">
        <div class="mx-3 sidebar-brand-text ttn d-flex align-items-center justify-content-center">
            <div class="center d-flex align-items-center justify-content-center">
                <img width="80%" class="py-2 my-2" style="border-radius: 10px" src="{{ asset(config('app.logo')) }}"
                    alt="">
            </div>
        </div>
    </a>

    <!-- Divider -->
    <hr class="my-0 sidebar-divider">

    <!-- Dashboard -->
    <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-home"></i>
            <span> {{ __('Dashboard') }} </span>
        </a>
    </li>

    @can('companies')
        <li class="nav-item {{ Route::is('admins.companies.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.companies.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecompanies"
               aria-expanded="true" aria-controls="collapsecompanies">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Companies Section') }}</span>
            </a>
            <div id="collapsecompanies" class="collapse {{ Route::is('admins.companies.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.companies.index') }}">{{ __('Companies') }}</a>
                    <a class="collapse-item"
                       href="{{ route('admins.companies.create') }}">{{ __('Create A Company') }}</a>
                </div>
            </div>
        </li>
    @endcan

    @can('iqamaTypes')
        <li class="nav-item {{ Route::is('admins.types.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.types.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsetypes"
               aria-expanded="true" aria-controls="collapsetypes">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Iqama Types Section') }}</span>
            </a>
            <div id="collapsetypes" class="collapse {{ Route::is('admins.types.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.types.index') }}">{{ __('Iqama Types') }}</a>
                    <a class="collapse-item" href="{{ route('admins.types.create') }}">{{ __('Create A Iqama Type') }}</a>
                </div>
            </div>
        </li>
    @endcan

   @can('employees')
        <li class="nav-item {{ Route::is('admins.employees.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.employees.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseemployees"
               aria-expanded="true" aria-controls="collapseemployees">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Employees Section') }}</span>
            </a>
            <div id="collapseemployees" class="collapse {{ Route::is('admins.employees.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.employees.index') }}">{{ __('All Employees') }}</a>
                    <a class="collapse-item"
                       href="{{ route('admins.employees.create') }}">{{ __('Create An Employee') }}</a>
                </div>
            </div>
        </li>
   @endcan

    @can('stages')
        <li class="nav-item {{ Route::is('admins.stages.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.stages.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsestages"
               aria-expanded="true" aria-controls="collapsestages">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Stages Section') }}</span>
            </a>
            <div id="collapsestages" class="collapse {{ Route::is('admins.stages.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.stages.index') }}">{{ __('All Stages') }}</a>
                    <a class="collapse-item" href="{{ route('admins.stages.create') }}">{{ __('Create A Stage') }}</a>
                </div>
            </div>
        </li>

        <li class="nav-item {{ Route::is('admins.employee-stages.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.employee-stages.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseemployeeStages"
               aria-expanded="true" aria-controls="collapseemployeeStages">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Employee Stages') }}</span>
            </a>
            <div id="collapseemployeeStages" class="collapse {{ Route::is('admins.employee-stages.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item"
                       href="{{ route('admins.employee-stages.getSingleEmployee') }}">{{ __('Single Employee') }}</a>
                    <a class="collapse-item"
                       href="{{ route('admins.employee-stages.getPendingJobs') }}">{{ __('Pending Jobs') }}</a>
                </div>
            </div>
        </li>
    @endcan

    @can('paymentAccounts')
        <li class="nav-item {{ Route::is('admins.paymentAccounts.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.paymentAccounts.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsepaymentAccounts"
               aria-expanded="true" aria-controls="collapsepaymentAccounts">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Payment Accounts') }}</span>
            </a>
            <div id="collapsepaymentAccounts" class="collapse {{ Route::is('admins.paymentAccounts.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item"
                       href="{{ route('admins.paymentAccounts.index') }}">{{ __('All Payment Accounts') }}</a>
                    <a class="collapse-item"
                       href="{{ route('admins.paymentAccounts.create') }}">{{ __('Create A Payment Account') }}</a>
                </div>
            </div>
        </li>
    @endcan

    @can('admins')
        <li class="nav-item {{ Route::is('admins.admins.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.admins.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseadmins"
               aria-expanded="true" aria-controls="collapseadmins">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Admins Section') }}</span>
            </a>
            <div id="collapseadmins" class="collapse {{ Route::is('admins.admins.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.admins.index') }}">{{ __('All Admins') }}</a>
                    <a class="collapse-item" href="{{ route('admins.admins.create') }}">{{ __('Create New Admin') }}</a>
                </div>
            </div>
        </li>
    @endcan

    @can('moderators')
        <li class="nav-item {{ Route::is('admins.moderators.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.moderators.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseModerators"
               aria-expanded="true" aria-controls="collapseModerators">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Moderators Section') }}</span>
            </a>
            <div id="collapseModerators" class="collapse {{ Route::is('admins.moderators.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.moderators.index') }}">{{ __('All Moderators') }}</a>
                    <a class="collapse-item" href="{{ route('admins.moderators.create') }}">{{ __('Create New Moderator') }}</a>
                </div>
            </div>
        </li>
    @endcan

    <!-- Divider -->
    @can('leaves')
        <li class="nav-item {{ Route::is('admins.hr.leaves.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.hr.leaves.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHR"
               aria-expanded="true" aria-controls="collapseHR">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('HR Section') }}</span>
            </a>
            <div id="collapseHR" class="collapse {{ Route::is('admins.hr.leaves.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.hr.leaves.index') }}">{{ __('All Leaves') }}</a>
                    <a class="collapse-item" href="{{ route('admins.hr.leaves.create') }}">{{ __('Create New Leave') }}</a>
                </div>
            </div>
        </li>
    @endcan

    @can('paySalaries')
        <li class="nav-item {{ Route::is('admins.hr.salaries.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.hr.salaries.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSalaries"
               aria-expanded="true" aria-controls="collapseSalaries">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Salaries Section') }}</span>
            </a>
            <div id="collapseSalaries" class="collapse {{ Route::is('admins.hr.salaries.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.hr.salaries.index') }}">{{ __('All Salaries ') }}</a>
                </div>
            </div>
        </li>
    @endcan

    @can('eos')
        <li class="nav-item {{ Route::is('admins.eos.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.eos.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEOS"
               aria-expanded="true" aria-controls="collapseEOS">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('EOS Section') }}</span>
            </a>
            <div id="collapseEOS" class="collapse {{ Route::is('admins.eos.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    <a class="collapse-item" href="{{ route('admins.eos.index') }}">{{ __('All EOS') }}</a>
                    <a class="collapse-item" href="{{ route('admins.eos.create') }}">{{ __('Create New EOS') }}</a>
                </div>
            </div>
        </li>
    @endcan



    @canany(['eos', 'leaves', 'employees'])
        <li class="nav-item {{ Route::is('admins.reports.*') ? 'active' : '' }}"
            style="{{ Route::is('admins.reports.*') ? 'background-color: #ccad75;' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReports"
               aria-expanded="true" aria-controls="collapseReports">
                <i class="far fa-caret-square-right"></i>
                <span>{{ __('Reports Section') }}</span>
            </a>
            <div id="collapseReports" class="collapse {{ Route::is('admins.reports.*') ? 'show' : '' }}"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="py-2 bg-white rounded collapse-inner">
                    @can('eos')
                        <a class="collapse-item" href="{{ route('admins.reports.eos.report') }}">{{ __('EOS Report') }}</a>
                    @endcan
                    @can('leaves')
                        <a class="collapse-item" href="{{ route('admins.reports.leaves.report') }}">{{ __('Leaves Report') }}</a>
                    @endcan
                    @can('employees')
                        <a class="collapse-item" href="{{ route('admins.reports.employees.report') }}">{{ __('Employees Report') }}</a>
                        <a class="collapse-item" href="{{ route('admins.reports.employee.details') }}">{{ __('Employee Details') }}</a>
                    @endcan
                        @hasrole('super-admin|admin')<a class="collapse-item" href="{{ route('admins.reports.transactions.report') }}">{{ __('Payment Accounts Transactions Report') }}</a>@endhasrole
                        @hasrole('super-admin|admin')<a class="collapse-item" href="{{ route('admins.reports.profit.report') }}">{{ __('Profit Report') }}</a>@endhasrole
                        @hasrole('super-admin|admin')<a class="collapse-item" href="{{ route('admins.reports.wallet-transactions.report') }}">{{ __('Wallet Transactions Report') }}</a>@endhasrole
                </div>
            </div>
        </li>
    @endcanany

{{--    @can('roles')--}}
{{--        <li class="nav-item {{ Route::is('admins.roles.*') ? 'active' : '' }}"--}}
{{--            style="{{ Route::is('admins.roles.*') ? 'background-color: #ccad75;' : '' }}">--}}
{{--            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRoles"--}}
{{--               aria-expanded="true" aria-controls="collapseRoles">--}}
{{--                <i class="far fa-caret-square-right"></i>--}}
{{--                <span>{{ __('Roles Section') }}</span>--}}
{{--            </a>--}}
{{--            <div id="collapseRoles" class="collapse {{ Route::is('admins.roles.*') ? 'show' : '' }}"--}}
{{--                 aria-labelledby="headingPages" data-parent="#accordionSidebar">--}}
{{--                <div class="py-2 bg-white rounded collapse-inner">--}}
{{--                    @can('eos')--}}
{{--                        <a class="collapse-item" href="{{ route('admins.roles.index') }}">{{ __('Manage Roles') }}</a>--}}
{{--                        <a class="collapse-item" href="{{ route('admins.roles.permissions') }}">{{ __('Manage Permissions') }}</a>--}}
{{--                    @endcan--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </li>--}}
{{--    @endcan--}}
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="border-0 rounded-circle" id="sidebarToggle"></button>
    </div>
</ul>
