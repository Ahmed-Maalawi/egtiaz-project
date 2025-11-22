<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::latest()->get();

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'status' => 'required|in:active,inactive',
            'image' => 'required|image|max:5120',
            'banner_image' => 'nullable|image|max:5120',
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('companies', 'public');
        }

        $banner_path = null;
        if ($request->hasFile('banner_image')) {
            $banner_image = $request->file('banner_image');
            $banner_path = $banner_image->store('companies', 'public');
        }

        Company::create([
            'name' => [
                'ar' => $request->name_ar,
                'en' => $request->name_en,
            ],
            'description' => [
                'ar' => $request->description_ar,
                'en' => $request->description_en,
            ],
            'status' => $request->status,
            'image' => $image_path,
            'banner_image' => $banner_path,
        ]);

        return redirect()->route('admins.companies.index')
            ->with('success', __('Company Added Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $company = Company::with([
            'wallet.walletTransactions.employeeStage.employee',
            'wallet.walletTransactions.employeeStage.stage',
            'wallet.walletTransactions.user',
            'employees.stages.stage',
            'employees.stages.transactions',
            'moderators',
        ])->findOrFail($id);

        $walletTransactions = $company->wallet->walletTransactions()
            ->with(['employeeStage.employee', 'employeeStage.stage', 'user'])
            ->latest()
            ->get();

        $paymentTransactions = Transaction::whereHas('employeeStage.employee', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })
            ->with(['employeeStage.employee', 'employeeStage.stage', 'paymentAccount', 'createdBy'])
            ->latest()
            ->get();

        $debitTransactions = $company->wallet->walletTransactions()
            ->with(['employeeStage.employee', 'employeeStage.stage', 'user'])
            ->whereNotNull('created_at')
            ->where('type', 'stage_payment')
            ->get();

        $creditTransactions = $company->wallet->walletTransactions()
            ->with(['employeeStage.employee', 'employeeStage.stage', 'user'])
            ->whereNotNull('created_at')
            ->whereNull('type')
            ->get();

        $summary = $this->calculateCompanySummary($company);

        $employeeProfits = $this->getEmployeeProfitReport($company);

        return view('admin.companies.show', compact(
            'company',
            'walletTransactions',
            'paymentTransactions',
            'summary',
            'employeeProfits',
            'debitTransactions',
            'creditTransactions'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:5120',
            'banner_image' => 'nullable|image|max:5120',
        ]);

        $image_path = $company->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('companies', 'public');

            if ($company->image) {
                Controller::deleteFile($company->image);
            }
        }

        $banner_path = $company->banner_image;
        if ($request->hasFile('banner_image')) {
            $banner_image = $request->file('banner_image');
            $banner_path = $banner_image->store('companies', 'public');
            if ($company->banner_image) {
                Controller::deleteFile($company->banner_image);
            }
        }

        $company->update([
            'name' => [
                'ar' => $request->name_ar,
                'en' => $request->name_en,
            ],
            'description' => [
                'ar' => $request->description_ar,
                'en' => $request->description_en,
            ],
            'status' => $request->status,
            'image' => $image_path,
            'banner_image' => $banner_path,
        ]);

        return redirect()->route('admins.companies.index')
            ->with('success', __('Company Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        if ($company->image) {
            Controller::deleteFile($company->image);
        }

        if ($company->banner_image) {
            Controller::deleteFile($company->banner_image);
        }

        $company->delete();

        return redirect()->route('admins.companies.index')
            ->with('success', __('Company Deleted Successfully'));
    }

    public function toggleStatus($id)
    {
        $company = Company::findOrFail($id);

        $company->status = $company->status == 'active' ? 'inactive' : 'active';
        $company->save();

        return response()->json([
            'message' => __('company status updated'),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string',
        ]);

        $locale = app()->getLocale();

        $search = "%{$request->query('q')}%";

        $companies = Company::where("name->{$locale}", 'like', $search)->limit(10)->get();

        return response()->json(
            $companies->map(function ($company) use ($locale) {
                return [
                    'id' => $company->id,
                    'name' => $company->getTranslation('name', $locale),
                ];
            })
        );
    }

    private function calculateCompanySummary($company)
    {

        $completedStages = \App\Models\EmployeeStage::whereHas('employee', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })
            ->where('status', 'completed')
            ->get();

        $totalWalletCharges = $company->wallet->walletTransactions()
            ->where('type', 'stage_payment')
            ->sum('amount');

        $totalCosts = Transaction::whereHas('employeeStage.employee', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })
            ->where('type', 'stage_payment')
            ->sum('amount');

        $totalProfit = $totalWalletCharges - $totalCosts;

        return [
            'total_employees' => $company->employees->count(),
            'total_stages_completed' => $completedStages->count(),
            'total_cost' => $totalCosts,
            'total_price' => $totalWalletCharges,
            'total_profit' => $totalProfit,
            'profit_margin' => $totalWalletCharges > 0 ? ($totalProfit / $totalWalletCharges) * 100 : 0,
            'current_wallet_balance' => $company->wallet->balance,
            'total_wallet_charges' => $company->wallet->walletTransactions()
                ->whereNull('type') // Only charges, not stage payments
                ->sum('amount'),
        ];
    }

    private function getEmployeeProfitReport($company)
    {
        return $company->employees()
            ->with(['stages' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->get()
            ->map(function ($employee) {
                $completedStages = $employee->stages->where('status', 'completed');
                $totalCost = $completedStages->sum('amount_cost');
                $totalPrice = $completedStages->sum('price_amount');

                return [
                    'employee' => $employee,
                    'completed_stages' => $completedStages->count(),
                    'total_cost' => $totalCost,
                    'total_price' => $totalPrice,
                    'total_profit' => $totalPrice - $totalCost,
                ];
            })
            ->filter(function ($item) {
                return $item['completed_stages'] > 0;
            });
    }

    public function downloadInvoice($transactionId)
    {
        $transaction = WalletTransaction::with([
            'employeeStage.employee.company',
            'employeeStage.stage',
        ])->findOrFail($transactionId);

        // Configure mPDF for Arabic/RTL support
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'xbriyaz', // Arabic font
            'direction' => 'rtl', // Right-to-left for Arabic
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);

        // Load your existing Blade view
        $html = View::make('admin.invoices.wallet-transaction', compact('transaction'))->render();

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('invoice-'.$transactionId.'.pdf', 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Get transaction details for modal
     */
    public function getTransactionDetails($type, $id)
    {
        if ($type === 'wallet') {
            $transaction = WalletTransaction::with([
                'employeeStage.employee',
                'employeeStage.stage',
                'wallet',
            ])->findOrFail($id);

            // Find related payment transaction
            $paymentTransaction = null;
            if ($transaction->employeeStage_id) {
                $paymentTransaction = Transaction::where('employee_stage_id', $transaction->employee_stage_id)
                    ->where('type', 'stage_payment')
                    ->with('paymentAccount')
                    ->first();
            }

            return response()->json([
                'employee_name' => $transaction->employeeStage->employee->name ?? 'N/A',
                'stage_name' => $transaction->employeeStage->stage->name ?? 'N/A',
                'cost' => number_format($paymentTransaction ? $paymentTransaction->amount : 0, 2),
                'price' => number_format($transaction->amount, 2),
                'profit' => number_format($transaction->amount - ($paymentTransaction ? $paymentTransaction->amount : 0), 2),
                'payment_account' => $paymentTransaction && $paymentTransaction->paymentAccount
                    ? $paymentTransaction->paymentAccount->name
                    : 'N/A',
                'wallet_balance' => number_format($transaction->wallet->balance ?? 0, 2),
                'status' => ucfirst($transaction->status),
                'date' => $transaction->completed_at->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json(['error' => 'Invalid transaction type'], 400);
    }

    /**
     * Export transactions to Excel
     */
    //    public function exportTransactions($companyId)
    //    {
    //        $company = Company::findOrFail($companyId);
    //
    //        $walletTransactions = $company->wallet->walletTransactions()
    //            ->with(['employeeStage.employee', 'employeeStage.stage'])
    //            ->get();
    //
    //        return \Excel::download(
    //            new \App\Exports\CompanyTransactionsExport($walletTransactions, $company),
    //            'company-' . $company->id . '-transactions.xlsx'
    //        );
    //    }

    /**
     * Get all company report data in one function
     */
    private function getCompanyReportData($id, $forPdf = false)
    {
        $company = Company::with([
            'employees',
            'employees.stages.stage',
            'wallet',
            'wallet.walletTransactions',
            'employees.stages' => function ($query) {
                $query->with('stage');
            },
        ])->findOrFail($id);

        // Summary Data
        $summary = $this->calculateSummary($company);

        // Wallet Transactions (with or without pagination)
        $walletTransactions = $this->getWalletTransactions($company, $forPdf);

        // Payment Transactions (with or without pagination)
        $paymentTransactions = $this->getPaymentTransactions($company, $forPdf);

        // Employee Profits
        $employeeProfits = $this->getEmployeeProfits($company);

        return [
            'company' => $company,
            'summary' => $summary,
            'walletTransactions' => $walletTransactions,
            'paymentTransactions' => $paymentTransactions,
            'employeeProfits' => $employeeProfits,
            'date' => now()->format('F j, Y'),
        ];
    }

    /**
     * Get wallet transactions (paginated for web, all for PDF)
     */
    private function getWalletTransactions($company, $forPdf = false)
    {
        $query = WalletTransaction::with([
            'employeeStage.employee.user',
            'employeeStage.stage',
            'user',
        ])
            ->where(function ($query) use ($company) {
                $query->whereHas('employeeStage.employee', function ($q) use ($company) {
                    $q->where('company_id', $company->id);
                })
                    ->orWhere('wallet_id', $company->wallet->id ?? 0);
            })
            ->orderBy('created_at', 'desc');

        return $forPdf ? $query->get() : $query->paginate(10);
    }

    /**
     * Get payment transactions (paginated for web, all for PDF)
     */
    private function getPaymentTransactions($company, $forPdf = false)
    {
        $query = Transaction::with([
            'employeeStage.employee.user',
            'employeeStage.stage',
            'paymentAccount',
            'createdBy',
        ])
            ->whereHas('employeeStage.employee', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->orderBy('created_at', 'desc');

        return $forPdf ? $query->get() : $query->paginate(10);
    }

    /**
     * Get employee profits data
     */
    private function getEmployeeProfits($company)
    {
        return $company->employees->map(function ($employee) {
            $completedStages = $employee->stages->where('status', 'completed');

            $totalCost = $completedStages->sum('amount_cost'); // Use amount_cost instead of amount_cost
            $totalPrice = $completedStages->sum(function ($stage) {
                return $stage->walletTransaction->amount ?? 0;
            });
            $totalProfit = $totalPrice - $totalCost;

            return [
                'employee' => $employee,
                'completed_stages' => $completedStages->count(),
                'total_cost' => $totalCost,
                'total_price' => $totalPrice,
                'total_profit' => $totalProfit,
            ];
        })->filter(function ($item) {
            return $item['completed_stages'] > 0;
        });
    }

    /**
     * Generate PDF report
     */
    public function generateCompanyReport($companyId)
    {
        //        try {
        // Load all necessary data
        $company = Company::with([
            'wallet.walletTransactions.employeeStage.employee',
            'wallet.walletTransactions.employeeStage.stage',
            'wallet.walletTransactions.user',
            'employees.stages.stage',
            'employees.stages.transactions',
            'moderators',
        ])->findOrFail($companyId);

        // Get all transactions without pagination for PDF
        $walletTransactions = $company->wallet->walletTransactions()
            ->with(['employeeStage.employee', 'employeeStage.stage', 'user'])
            ->latest()
            ->get();

        $paymentTransactions = Transaction::whereHas('employeeStage.employee', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })
            ->with(['employeeStage.employee', 'employeeStage.stage', 'paymentAccount', 'createdBy'])
            ->latest()
            ->get();

        // Get debit and credit transactions
        $debitTransactions = $company->wallet->walletTransactions()
            ->with(['employeeStage.employee', 'employeeStage.stage', 'user'])
            ->whereNotNull('created_at')
            ->where('type', 'stage_payment')
            ->get();

        $creditTransactions = $company->wallet->walletTransactions()
            ->with(['employeeStage.employee', 'employeeStage.stage', 'user'])
            ->whereNotNull('created_at')
            ->whereNull('type')
            ->get();

        // Calculate summary
        $summary = $this->calculateCompanySummary($company);

        // Get employee profits
        $employeeProfits = $this->getEmployeeProfitReport($company);

        // Configure mPDF with better settings
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-P', // Landscape for better table display
            'default_font' => app()->getLocale() === 'ar' ? 'xbriyaz' : 'dejavusans',
            'direction' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_header' => 5,
            'margin_footer' => 5,
            'tempDir' => storage_path('app/tmp'),
        ]);

        // Generate HTML from Blade view
        $html = View::make('admin.companies.reports.company-details', [
            'company' => $company,
            'summary' => $summary,
            'walletTransactions' => $walletTransactions,
            'paymentTransactions' => $paymentTransactions,
            'employeeProfits' => $employeeProfits,
            'debitTransactions' => $debitTransactions,
            'creditTransactions' => $creditTransactions,
            'generatedDate' => now()->format('Y-m-d H:i:s'),
        ])->render();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        $filename = 'company-report-'.$company->id.'-'.now()->format('Y-m-d').'.pdf';

        // Return PDF as download
        return response($mpdf->Output($filename, 'D'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');

        //        } catch (\Exception $e) {
        //            \Log::error('PDF Generation Error: ' . $e->getMessage(), [
        //                'trace' => $e->getTraceAsString(),
        //                'company_id' => $companyId
        //            ]);
        //
        //            return back()->with('error', __('Failed to generate PDF report. Please try again.'));
        //        }
    }

    /**
     * Download PDF report (route handler)
     */
    public function downloadReport($companyId)
    {
        return $this->generateCompanyReport($companyId);
    }

    private function calculateSummary($company)
    {
        // Total employees
        $totalEmployees = $company->employees->count();

        try {
            // Get wallet transactions stats with proper joins
            $walletStats = WalletTransaction::join('employee_stages', 'wallet_transactions.employee_stage_id', '=', 'employee_stages.id')
                ->join('employees', 'employee_stages.employee_id', '=', 'employees.id')
                ->where('employees.company_id', $company->id)
                ->select(
                    DB::raw('COALESCE(SUM(wallet_transactions.amount), 0) as total_price'),
                    DB::raw('COALESCE(SUM(employee_stages.amount_cost), 0) as total_cost')
                )
                ->first();

            $totalPrice = $walletStats->total_price ?? 0;
            $totalCost = $walletStats->total_cost ?? 0;

        } catch (\Exception $e) {
            \Log::error('Error calculating wallet stats: '.$e->getMessage());
            $totalPrice = 0;
            $totalCost = 0;
        }

        $totalProfit = $totalPrice - $totalCost;
        $profitMargin = $totalPrice > 0 ? ($totalProfit / $totalPrice) * 100 : 0;

        // Current wallet balance
        $currentWalletBalance = $company->wallet->balance ?? 0;

        // Total wallet charges (transactions where employee_stage_id is null)
        $totalWalletCharges = WalletTransaction::where('wallet_id', $company->wallet->id ?? 0)
            ->whereNull('employee_stage_id')
            ->where('status', 'completed')
            ->sum('amount');

        // Total stages completed
        $totalStagesCompleted = DB::table('employee_stages')
            ->join('employees', 'employee_stages.employee_id', '=', 'employees.id')
            ->where('employees.company_id', $company->id)
            ->where('employee_stages.status', 'completed')
            ->count();

        return [
            'total_employees' => $totalEmployees,
            'total_cost' => $totalCost,
            'total_price' => $totalPrice,
            'total_profit' => $totalProfit,
            'profit_margin' => $profitMargin,
            'current_wallet_balance' => $currentWalletBalance,
            'total_wallet_charges' => $totalWalletCharges,
            'total_stages_completed' => $totalStagesCompleted,
        ];
    }
}
