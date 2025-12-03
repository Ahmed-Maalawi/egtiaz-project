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

        @media print {
            /* Hide everything except the printable section */
            body * {
                visibility: hidden;
            }

            #employeeProfile, #employeeProfile * {
                visibility: visible;
            }

            #employeeProfile {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
                background: white;
            }

            #viewStageFiles {
                display: none !important;
            }
            /* Hide elements that shouldn’t appear in print */
            #printReportBtn,
            .employee-selector,
            .header-section {
                display: none !important;
            }

            /* Remove card hover and shadows for print clarity */
            .info-card, .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .card-header-custom {
                background: #3498db !important;
                color: white !important;
            }

            .profile-header {
                background: #2c3e50 !important;
                color: white !important;
                box-shadow: none !important;
            }

            /* Page setup */
            @page {
                margin: 15mm;
            }
            /* Ensure table footer is visible in PDF */
            tfoot { display: table-row-group; }
        }
    </style>

    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="lead">{{ __('View employee information and track iqama stages') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Employee Selector -->
        <div class="employee-selector">
            <h6 class="mb-3"><i class="fas fa-search mr-2"></i>{{ __('Select an Employee') }}</h6>
            <form id="employeeForm">
                <div class="form-group">
                    <label for="employeeSelect" class="font-weight-bold">{{ __('Search for Employee:') }}</label>
                    <select class="form-control select2" id="employeeSelect" name="employee_id">
                        <option value="">{{ __('-- Select Employee --') }}</option>
                        @foreach($employees as $index => $emp)
                            <option value="{{ $emp->id }}" {{ $employee?->id === $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row justify-content-between">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary my-2" id="view-profile-btn">
                            <i class="fas fa-user-check mr-2"></i>{{ __('View Profile') }}
                        </button>
                    </div>

                        @if($employee)
                        <div class="col-md-3">
                            <div class="text-right mb-3">
                                <button class="btn btn-outline-primary mb-3" id="printReport">
                                    <i class="fas fa-print mr-2"></i> {{ __('Print Report') }}
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
            </form>
        </div>

        @if($employee)
            <div id="employeeProfile" class="{{ $employee ? '' : 'd-none' }}">
                <div class="profile-header">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="profile-avatar" id="profileAvatar">
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
                                <i class="fas fa-id-card mr-2"></i>{{ __('Personal Information') }}
                            </div>
                            <div class="card-body p-0">
                                <div class="info-item">
                                    <span class="info-label">{{ __('Email:') }}</span>
                                    <span id="infoEmail">{{ $employee->email }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('Phone Number:') }}</span>
                                    <span id="infoPhone">{{ $employee->phone }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('Salary:') }}</span>
                                    <span id="infoSalary">{{ $employee->salary . ' ' . __('AED') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('Address:') }}</span>
                                    <span id="infoAddress">{{ $employee->address }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('Gender:') }}</span>
                                    <span id="infoGender">{{ $employee->gender }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('Passport Number:') }}</span>
                                    <span id="infoPassport">{{ $employee->passport_number }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('Expiry Date:') }}</span>
                                    <span id="infoExpiry">{{ \Illuminate\Support\Carbon::parse($employee->expired_date)->format('Y-m-d') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Company Information -->
                        <div class="info-card">
                            <div class="card-header card-header-custom">
                                <i class="fas fa-building mr-2"></i>{{ __('Company Information') }}
                            </div>
                            <div class="card-body p-0">
                                <div class="text-center py-3">
                                    <div class="company-logo" id="companyLogo">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h5 id="companyNameAr">{{ $employee->company->getTranslation('name', app()->getLocale()) }}</h5>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('Description:') }}</span>
                                    <span id="companyDescription">{{ $employee->company->getTranslation('description', app()->getLocale()) }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('Balance:') }}</span>
                                    <span id="companyBalance">{{ $employee->company->wallet->balance . ' ' . __('AED') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stages and Progress -->
                    <div class="col-lg-8">
                        <!-- Iqama Type -->
                        <div class="info-card mb-4">
                            <div class="card-header card-header-custom">
                                <i class="fas fa-file-contract mr-2"></i>{{ __('Iqama Type') }}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 id="iqamaNameAr">{{ $employee->IqamaType->getTranslation('name', app()->getLocale()) }}</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="iqamaDescription">{{ $employee->IqamaType->getTranslation('description', app()->getLocale()) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stages Progress -->
                        <div class="info-card mb-4">
                            <div class="card-header card-header-custom d-flex align-items-center">
                                <i class="fas fa-tasks mr-2"></i>
                                <span>{{ __('Iqama Stages') }}</span>
                            </div>

                            <div class="card-body">
                                @if($employee->employeeStages && $employee->employeeStages->count())
                                    <div class="timeline">
                                        @foreach($employee->employeeStages as $stage)
                                            <div class="timeline-item {{ $stage->status == 'completed' ? 'completed' : ($stage->status == 'in_progress' ? 'active' : '') }}">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h5 class="mb-0">
                                                            {{ $stage->stage->getTranslation('name', app()->getLocale()) }}
                                                        </h5>
                                                        <span class="badge
                                                            @if($stage->status == 'completed') badge-success
                                                            @elseif($stage->status == 'in_progress') badge-info
                                                            @else badge-secondary
                                                            @endif
                                                        ">
                                                            {{ ucfirst($stage->status) }}
                                                        </span>
                                                    </div>

                                                    @if($stage->expired_at)
                                                        <p class="text-muted mb-1">
                                                            <i class="fas fa-calendar-alt mr-1"></i>
                                                            {{ __('Expires at:') }} {{ $stage->expired_at->format('Y-m-d') }}
                                                        </p>
                                                    @endif

                                                    @if($stage->completed_at)
                                                        <p class="text-muted mb-1">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            {{ __('Completed at:') }} {{ $stage->completed_at->format('Y-m-d') }}
                                                        </p>
                                                    @endif

                                                    <p class="text-muted mb-1">
                                                        <i class="fas fa-money-bill mr-1"></i>
                                                        {{ __('Payment Status:') }}
                                                        <strong>{{ ucfirst($stage->status ?? 'Pending') }}</strong>
                                                    </p>

                                                    @if($stage->amount_paid)
                                                        <p class="text-muted mb-1">
                                                            <i class="fas fa-dollar-sign mr-1"></i>
                                                            {{ __('Amount Paid:') }} {{ number_format($stage->amount_paid, 2) }}
                                                        </p>
                                                    @endif

                                                    @if($stage->doneBy)
                                                        <p class="text-muted mb-1">
                                                            <i class="fas fa-user-check mr-1"></i>
                                                            {{ __('Done by:') }} {{ $stage?->doneBy?->name ?? 'N/A' }}
                                                        </p>
                                                    @endif

                                                    @if($stage->files->count())
                                                        <div class="mt-2">
                                                            <strong><i class="fas fa-paperclip mr-1"></i>{{ __('Files:') }}</strong>
                                                            <ul class="list-unstyled ml-3">
                                                                @foreach($stage->files as $file)
                                                                    <li>
                                                                        <a href="{{ asset($file->path) }}" target="_blank">
                                                                            <i class="fas fa-file mr-1"></i>{{ $file->name ?? basename($file->path) }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">{{ __('No stages found for this employee.') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Upcoming Stage -->
                            <div class="card-body" id="upcomingStageBody">
                                @if($employee->upcomingStage)
                                    @php $stage = $employee->upcomingStage; @endphp

                                    <div class="card shadow-sm border-0 mb-4">
                                        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-passport mr-2"></i>
                                                {{ __('Iqama Upcoming Stage') }}
                                            </div>
                                            <span class="badge
                                                @if($stage->status == 'completed') badge-success
                                                @elseif($stage->status == 'pending') badge-warning
                                                @else badge-info
                                                @endif
                                            ">
                                                {{ ucfirst($stage->status) }}
                                            </span>
                                        </div>

                                        <div class="card-body">
                                            {{-- Stage Name --}}
                                            <h4 class="text-primary mb-3">
                                                {{ $stage->stage->getTranslation('name', app()->getLocale()) }}
                                            </h4>

                                            {{-- Image --}}
                                            @if($stage->stage->image)
                                                <div class="text-center mb-3">
                                                    <img src="{{ asset($stage->stage->image) }}"
                                                         alt="Stage Image"
                                                         class="img-fluid rounded shadow-sm"
                                                         style="max-height: 200px;">
                                                </div>
                                            @endif

                                            {{-- Description --}}
                                            @if($stage->stage->description)
                                                <p class="text-muted">
                                                    {{ $stage->stage->getTranslation('description', app()->getLocale()) }}
                                                </p>
                                            @endif

                                            <hr>

                                            {{-- Stage Info --}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong><i class="fas fa-list-ol mr-1 text-secondary"></i>{{ __('Order:') }}</strong> {{ $stage->stage->order }}</p>
                                                    <p><strong><i class="fas fa-calendar-plus mr-1 text-secondary"></i>{{ __('Created At:') }}</strong> {{ $stage->created_at->format('Y-m-d') }}</p>
                                                    @if($stage->expired_at)
                                                        <p><strong><i class="fas fa-calendar-times mr-1 text-secondary"></i>{{ __('Expires At:') }}</strong> {{ $stage->expired_at->format('Y-m-d') }}</p>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <p><strong><i class="fas fa-money-bill mr-1 text-secondary"></i>{{ __('Price:') }}</strong> {{ number_format($stage->stage->price, 2) }}</p>
                                                    <p><strong><i class="fas fa-coins mr-1 text-secondary"></i>{{ __('Cost:') }}</strong> {{ number_format($stage->stage->cost, 2) }}</p>
                                                    <p><strong><i class="fas fa-wallet mr-1 text-secondary"></i>{{ __('Payment Status:') }}</strong> {{ ucfirst($stage->payment_status ?? 'Pending') }}</p>
                                                </div>
                                            </div>

                                            {{-- Done By --}}
                                            @if($stage->doneBy)
                                                <p><strong><i class="fas fa-user-check mr-1 text-secondary"></i>{{ __('Done By:') }}</strong> {{ $stage->doneBy->name }}</p>
                                            @endif

                                            {{-- Stage PDF File --}}
                                            @if($stage->stage->file)
                                                <div class="mt-3" id="viewStageFiles">
                                                    <a href="{{ asset( 'storage/'. $stage->stage->file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-file-pdf mr-1"></i>{{ __('View Stage File') }}
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- Employee Uploaded Files --}}
                                            @if($stage->files && $stage->files->count())
                                                <div class="mt-3">
                                                    <strong><i class="fas fa-paperclip mr-1"></i>{{ __('Uploaded Files:') }}</strong>
                                                    <ul class="list-unstyled ml-3">
                                                        @foreach($stage->files as $file)
                                                            <li>
                                                                <a href="{{ asset($file->path) }}" target="_blank">
                                                                    <i class="fas fa-file mr-1"></i>{{ $file->name ?? basename($file->path) }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">{{ __('No upcoming iqama stage found.') }}</p>
                                @endif
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
                    <h3 class="text-muted">{{ __('No employee selected') }}</h3>
                    <p class="text-muted">{{ __('Please select an employee from the list above to view their profile') }}</p>
                </div>
            </div>
        @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printBtn = document.getElementById('printReport');
            const profileSection = document.getElementById('employeeProfile');



            if (!printBtn || !profileSection) return;

            printBtn.addEventListener('click', async function () {

                const clone = profileSection.cloneNode(true);

                const styles = Array.from(document.querySelectorAll('style, link[rel="stylesheet"]'))
                    .map(el => el.outerHTML)
                    .join('\n');


                const printable = document.createElement('div');
                printable.innerHTML = `
            <html>
                <head>${styles}</head>
                <body>${clone.outerHTML}</body>
            </html>
        `;


                // Options for html2pdf
                const opt = {
                    margin: 0.3,
                    filename: `employee-report.pdf`,
                    image: { type: 'jpeg', quality: 0.8 }, // reduce quality a bit
                    html2canvas: { scale: 2, useCORS: true, logging: false }, // reduce scale
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
                };

                printBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';
                printBtn.disabled = true;

                try {
                    await html2pdf().set(opt).from(printable).save();
                } catch (err) {
                    console.error('PDF generation failed:', err);
                    alert('Error generating PDF. Check the console for details.');
                } finally {
                    printBtn.innerHTML = '<i class="fas fa-print mr-2"></i> Print Report';
                    printBtn.disabled = false;
                }
            });
        });
    </script>


</x-dashboard.main-layout>



