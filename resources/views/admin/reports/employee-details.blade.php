<x-dashboard.main-layout>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            border-radius: 0 0 20px 20px;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.3);
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--secondary-color);
            margin: 0 auto;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
            border: none;
        }

        .info-card:hover {
            transform: translateY(-5px);
        }

        .card-header-custom {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .info-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .stage-card {
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1rem;
        }

        .stage-completed {
            border-left-color: #28a745;
        }

        .stage-current {
            border-left-color: var(--accent-color);
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.2);
        }

        .progress-tracker {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 2rem 0;
        }

        .progress-tracker::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 4px;
            background-color: #e9ecef;
            z-index: 1;
        }

        .progress-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            color: #6c757d;
            font-weight: bold;
        }

        .step-active .step-icon {
            background-color: var(--primary-color);
            color: white;
        }

        .step-completed .step-icon {
            background-color: #28a745;
            color: white;
        }

        .step-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .step-active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .step-completed .step-label {
            color: #28a745;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            background-color: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--secondary-color);
            margin: 0 auto 1rem;
        }

        @media (max-width: 768px) {
            .profile-avatar {
                width: 120px;
                height: 120px;
                font-size: 3rem;
            }

            .progress-tracker {
                flex-wrap: wrap;
            }

            .progress-step {
                flex: 0 0 33.333%;
                margin-bottom: 1rem;
            }
        }
    </style>
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4">نظام إدارة الموظفين</h1>
                    <p class="lead">عرض معلومات الموظفين وتتبع مراحل الإقامة</p>
                </div>
                <div class="col-md-4 text-left">
                    <i class="fas fa-users fa-5x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Employee Selector -->
        <div class="employee-selector">
            <h3 class="mb-3"><i class="fas fa-search mr-2"></i>اختر موظفاً</h3>
            <form id="employeeForm">
                <div class="form-group">
                    <label for="employeeSelect" class="font-weight-bold">بحث عن الموظف:</label>
                    <select class="form-control select2" id="employeeSelect" name="employee_id">
                        <option value="">-- اختر موظفا --</option>
                        <!-- Sample employees data - in real app this would come from backend -->
                        @foreach($employees as $index => $emp)
                            <option value="{{$emp->id}}" {{ $employee->id === $emp->id? 'select' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-user-check mr-2"></i>عرض الملف الشخصي
                </button>
            </form>
        </div>

        <!-- Employee Profile (initially hidden) -->
        @if($employee)
            <div id="employeeProfile" class="{{ $employee ? '' : 'd-none' }}">
                <div class="profile-header">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="profile-avatar" id="profileAvatar">
{{--                                    <img src="{{ asset('storage/' . $employee->image) }}" alt="">--}}
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h1 class="display-4" id="employeeName">{{ $employee->name }}</h1>
                                <p class="lead mb-1" id="employeeCompany">{{ $employee->company->name }}</p>
                                <p class="mb-0">
                                    <span class="status-badge status-active" id="employeeStatus">{{ __($employee->status) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Personal Information -->
                    <div class="col-lg-4 mb-4">
                        <div class="info-card">
                            <div class="card-header card-header-custom">
                                <i class="fas fa-id-card mr-2"></i>المعلومات الشخصية
                            </div>
                            <div class="card-body p-0">
                                <div class="info-item">
                                    <span class="info-label">البريد الإلكتروني:</span>
                                    <span id="infoEmail">{{ $employee->email }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">رقم الهاتف:</span>
                                    <span id="infoPhone">{{ $employee->phone }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">الراتب:</span>
                                    <span id="infoSalary">{{ $employee->salary . ' ' .  __('SAR')}}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">العنوان:</span>
                                    <span id="infoAddress">{{ $employee->address }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">الجنس:</span>
                                    <span id="infoGender">{{ $employee->gender }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">رقم الجواز:</span>
                                    <span id="infoPassport">{{ $employee->passport_number }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">تاريخ الانتهاء:</span>
                                    <span id="infoExpiry">{{ $employee->expired_date }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Company Information -->
                        <div class="info-card">
                            <div class="card-header card-header-custom">
                                <i class="fas fa-building mr-2"></i>معلومات الشركة
                            </div>
                            <div class="card-body p-0">
                                <div class="text-center py-3">
                                    <div class="company-logo" id="companyLogo">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h5 id="companyNameAr">{{ $employee->company->getTranslation('name', app()->getLocale()) }}</h5>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">الوصف:</span>
                                    <span id="companyDescription">{{ $employee->company->getTranslation('description', app()->getLocale()) }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">الرصيد:</span>
                                    <span id="companyBalance">{{ $employee->company->wallet->balance . ' ' . __('SAR') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stages and Progress -->
                    <div class="col-lg-8">
                        <!-- Iqama Type -->
                        <div class="info-card mb-4">
                            <div class="card-header card-header-custom">
                                <i class="fas fa-file-contract mr-2"></i>نوع الإقامة
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 id="iqamaNameAr">{{ $employee->IqamaType->getTranslation('name', app()->getLocale()) }}</h5>
{{--                                        <p class="text-muted" id="iqamaNameEn">medical</p>--}}
                                    </div>
                                    <div class="col-md-6">
                                        <p id="iqamaDescription">{{ $employee->IqamaType->getTranslation('description', app()->getLocale()) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stages Progress -->
                        <div class="info-card mb-4">
                            <div class="card-header card-header-custom">
                                <i class="fas fa-tasks mr-2"></i>مراحل الإقامة
                            </div>
                            <div class="card-body">
                                <!-- Progress Tracker -->
                                <div class="progress-tracker" id="progressTracker">
                                    <!-- Progress steps will be dynamically inserted here -->
                                </div>

                                <!-- Stages Details -->
                                <div id="stagesDetails">
                                    <!-- Stages will be dynamically inserted here -->
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Stage -->
                        <div class="info-card" id="upcomingStageCard">
                            <div class="card-header card-header-custom">
                                <i class="fas fa-arrow-circle-right mr-2"></i>المرحلة القادمة
                            </div>
                            <div class="card-body" id="upcomingStageBody">
                                <pre>{{$employee->upcomingStage->stage->getTranslation('name', app()->getLocale())}}</pre>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 id="iqamaNameAr">{{ $employee->upcomingStage }}</h5>
                                        {{--                                        <p class="text-muted" id="iqamaNameEn">medical</p>--}}
                                    </div>
                                    <div class="col-md-6">
                                        <p id="iqamaDescription">{{ $employee->upcomingStage }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        @else
            <!-- No Employee Selected Message -->
            <div id="noEmployeeMessage" class="text-center py-5">
                <div class="container">
                    <i class="fas fa-user-slash fa-5x text-muted mb-4"></i>
                    <h3 class="text-muted">لم يتم اختيار أي موظف</h3>
                    <p class="text-muted">يرجى اختيار موظف من القائمة أعلاه لعرض ملفه الشخصي</p>
                </div>
            </div>
        @endif
    </div>

</x-dashboard.main-layout>
