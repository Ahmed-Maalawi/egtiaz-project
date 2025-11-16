<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            'name_ar'                   => 'required|string',
            'name_en'                   => 'required|string',
            'description_ar'            => 'required|string',
            'description_en'            => 'required|string',
            'status'                    => 'required|in:active,inactive',
            'image'                     => 'required|image|max:5120',
            'banner_image'              => 'nullable|image|max:5120',
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
            'name'                  => [
                'ar'                    => $request->name_ar,
                'en'                    => $request->name_en,
            ],
            'description'           => [
                'ar'                    => $request->description_ar,
                'en'                    => $request->description_en,
            ],
            'status'                => $request->status,
            'image'                 => $image_path,
            'banner_image'          => $banner_path,
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
            'moderators'
        ])->findOrFail($id);

        // Get all wallet transactions with employee details
        $walletTransactions = $company->wallet->walletTransactions()
            ->with(['employeeStage.employee', 'employeeStage.stage', 'user'])
            ->latest()
            ->paginate(20);

        // Get all payment account transactions related to this company's employees
        $paymentTransactions = \App\Models\Transaction::whereHas('employeeStage.employee', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })
            ->with(['employeeStage.employee', 'employeeStage.stage', 'paymentAccount', 'createdBy'])
            ->latest()
            ->paginate(20);

        // Calculate summary statistics
        $summary = $this->calculateCompanySummary($company);

        // Get profit report by employee
        $employeeProfits = $this->getEmployeeProfitReport($company);

        return view('admin.companies.show', compact(
            'company',
            'walletTransactions',
            'paymentTransactions',
            'summary',
            'employeeProfits'
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
            'name_ar'                   => 'required|string',
            'name_en'                   => 'required|string',
            'description_ar'            => 'required|string',
            'description_en'            => 'required|string',
            'status'                    => 'required|in:active,inactive',
            'image'                     => 'nullable|image|max:5120',
            'banner_image'              => 'nullable|image|max:5120',
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
            'name'                  => [
                'ar'                    => $request->name_ar,
                'en'                    => $request->name_en,
            ],
            'description'           => [
                'ar'                    => $request->description_ar,
                'en'                    => $request->description_en,
            ],
            'status'                => $request->status,
            'image'                 => $image_path,
            'banner_image'          => $banner_path,
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
            'message'               => __('company status updated')
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q'                 =>'nullable|string',
        ]);

        $locale = app()->getLocale();

        $search = "%{$request->query('q')}%";

        $companies = Company::where("name->{$locale}",'like',$search)->limit(10)->get();

        return response()->json(
            $companies->map(function($company) use ($locale){
                return [
                    'id'                =>$company->id,
                    'name'              =>$company->getTranslation('name',$locale),
                ];
            })
        );
    }


    private function calculateCompanySummary($company)
    {
        // Get all completed stages for this company's employees
        $completedStages = \App\Models\EmployeeStage::whereHas('employee', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })
            ->where('status', 'completed')
            ->get();

        // Wallet transactions (what company paid us)
        $totalWalletCharges = $company->wallet->walletTransactions()
            ->where('type', 'stage_payment')
            ->sum('amount');

        // Payment transactions (what we paid for processing)
        $totalCosts = \App\Models\Transaction::whereHas('employeeStage.employee', function($query) use ($company) {
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
            ->with(['stages' => function($query) {
                $query->where('status', 'completed');
            }])
            ->get()
            ->map(function($employee) {
                $completedStages = $employee->stages->where('status', 'completed');
                $totalCost = $completedStages->sum('amount_cost');
                $totalPrice = $completedStages->sum('amount_paid');

                return [
                    'employee' => $employee,
                    'completed_stages' => $completedStages->count(),
                    'total_cost' => $totalCost,
                    'total_price' => $totalPrice,
                    'total_profit' => $totalPrice - $totalCost,
                ];
            })
            ->filter(function($item) {
                return $item['completed_stages'] > 0;
            });
    }

    public function downloadInvoice($transactionId)
    {
        $transaction = \App\Models\WalletTransaction::with([
            'employeeStage.employee.company',
            'employeeStage.stage'
        ])->findOrFail($transactionId);

        // Generate PDF invoice
        $pdf = \PDF::loadView('invoices.wallet-transaction', compact('transaction'));

        return $pdf->download('invoice-' . $transaction->id . '.pdf');
    }


    /**
     * Get transaction details for modal
     */
    public function getTransactionDetails($type, $id)
    {
        if ($type === 'wallet') {
            $transaction = \App\Models\WalletTransaction::with([
                'employeeStage.employee',
                'employeeStage.stage',
                'wallet'
            ])->findOrFail($id);

            // Find related payment transaction
            $paymentTransaction = null;
            if ($transaction->employeeStage_id) {
                $paymentTransaction = \App\Models\Transaction::where('employee_stage_id', $transaction->employee_stage_id)
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
}
