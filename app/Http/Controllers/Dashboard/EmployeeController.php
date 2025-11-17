<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Employee;
use App\Models\EmployeeFile;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\EmployeeStage;
use App\Models\IqamaType;
use App\Models\Stage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Mpdf\Mpdf;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with([
            'company',
            'iqamaType',
        ])->latest()->get();

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = IqamaType::select('id', 'name')->get();

        return view('admin.employees.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $active_companies = Company::active()->pluck('id')->toArray();

        $request->validate([
            'company_id'                        => ['required', Rule::in($active_companies)],
            'type_id'                           => 'required|exists:iqama_types,id',
            'name'                              => 'required|string|max:255',
            'email'                             => 'nullable|email|max:255',
            'phone'                             => 'nullable|string|max:20',
            'address'                           => 'nullable|string',
            'image'                             => 'nullable|image|max:5120',
            'passport_image'                    => 'nullable|image|max:5120',
            'passport_number'                   => 'nullable|string',
            'gender'                            => 'required|in:m,f',
            'status'                            => 'required|in:active,inactive',
            'expired_date'                      => 'nullable|date',
            'files'                             => 'nullable|array',
            'files.*'                           => 'required|file|mimes:pdf,png,jpg,jpeg|max:5120'
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('employees', 'public');
        }

        $passport_path = null;
        if ($request->hasFile('passport_image')) {
            $passport_image = $request->file('passport_image');
            $passport_path = $passport_image->store('passports', 'public');
        }

        $files_pathes = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $file_path = $file->storeAs(
                    'employee_files',
                    $request->name . $file->getClientOriginalName(),
                    'public'
                );
                $files_pathes[] = $file_path;
            }
        }


        try {
            DB::beginTransaction();
            $employee = Employee::create([
                'company_id'                        => $request->company_id,
                'iqama_type_id'                     => $request->type_id,
                'name'                              => $request->name,
                'email'                             => $request->email,
                'phone'                             => $request->phone,
                'address'                           => $request->address,
                'status'                            => $request->status,
                'expired_date'                      => $request->expired_date,
                'passport_image'                    => $passport_path,
                'passport_number'                   => $request->passport_number,
                'image'                             => $image_path,
                'gender'                            => $request->gender,
            ]);

            if (! empty($files_pathes)) {
                foreach ($files_pathes as $file) {
                    $employee->files()->create([
                        'path'              => $file,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admins.employees.index')
                ->with('success', __('Employee Added'));
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load(['company', 'iqamaType', 'upcomingStage.stage', 'files', 'employeeStages']);
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $employee->load([
            'company',
            'iqamaType'
        ]);

        $iqamaTypes = IqamaType::select('id', 'name')->get();

        return view('admin.employees.edit', compact('employee', 'iqamaTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'company_id'                        => 'required|exists:companies,id',
            'type_id'                           => 'required|exists:iqama_types,id',
            'name'                              => 'required|string|max:255',
            'email'                             => 'nullable|email|max:255',
            'phone'                             => 'nullable|string|max:20',
            'address'                           => 'nullable|string',
            'image'                             => 'nullable|image|max:5120',
            'passport_image'                    => 'nullable|image|max:5120',
            'passport_number'                   => 'nullable|string',
            'gender'                            => 'required|in:m,f',
            'status'                            => 'required|in:active,inactive',
            'expired_date'                      => 'nullable|date',
        ]);

        if ($employee->iqama_type_id != $request->type_id) {
            $employeeStages = EmployeeStage::where('employee_id', $employee->id)
                ->update([
                    'currently_type'                => false,
                ]);

            $stages = Stage::where('iqama_type_id', $request->type_id)->get();
            foreach ($stages as $stage) {
                EmployeeStage::create([
                    'employee_id'                   => $employee->id,
                    'stage_id'                      => $stage->id,
                    'status'                        => 'pending',
                ]);
            }
        }

        // Save new files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->storeAs(
                    'employee_files',
                    $request->name . $file->getClientOriginalName(),
                    'public'
                );
                $employee->files()->create([
                    'path' => $path,
                    'mime' => $file->getMimeType(),
                ]);
            }
        }

        if ($request->removed_files) {
            $ids = json_decode($request->removed_files, true);
            foreach ($ids as $id) {
                $file = $employee->files()->find($id);
                if ($file) {
                    Storage::disk('public')->delete($file->path);
                    $file->delete();
                }
            }
        }

        $image_path = $employee->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('employees', 'public');
            if ($employee->image) {
                Controller::deleteFile($employee->image);
            }
        }

        $passport_path = $employee->passport_image;
        if ($request->hasFile('passport_image')) {
            $passport_image = $request->file('passport_image');
            $passport_path = $passport_image->store('passports', 'public');
            if ($employee->passport_image) {
                Controller::deleteFile($employee->passport_image);
            }
        }

        $employee->update([
            'company_id'                        => $request->company_id,
            'iqama_type_id'                     => $request->type_id,
            'name'                              => $request->name,
            'email'                             => $request->email,
            'phone'                             => $request->phone,
            'address'                           => $request->address,
            'status'                            => $request->status,
            'expired_date'                      => $request->expired_date,
            'passport_image'                    => $passport_path,
            'passport_number'                   => $request->passport_number,
            'image'                             => $image_path,
            'gender'                            => $request->gender,
        ]);

        return redirect()->route('admins.employees.index')
            ->with('success', __('Employee Updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->image) {
            Controller::deleteFile($employee->image);
        }

        if ($employee->passport_image) {
            Controller::deleteFile($employee->passport_image);
        }

        $employee->delete();

        return redirect()->route('admins.employees.index')
            ->with('success', __('Employee Deleted'));
    }

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->status = $employee->status == 'active' ? 'inactive' : 'active';
        $employee->save();

        return response()->json([
            'message'                   => 'status updated',
        ]);
    }

    public function search(Request $request)
    {
        $locale = app()->getLocale();
        $search = "%{$request->query('q')}%";

        $employees = Employee::whereAny([
            'name',
            'email',
            'phone',
            'address',
            'passport_number'
        ], 'like', $search);

        $employees->when($request->query('company_id'), function ($employees) use ($request) {
            $employees->where('company_id', $request->query('company_id'));
        });

        $employees = $employees->limit(10)->select('id', 'name')->get();

        return response()->json(
            $employees->map(function ($employee){
                return [
                    'id'                => $employee->id,
                    'name'              => $employee->name,
                ];
            })
        );
    }

    public function showFiles(Employee $employee)
    {
        $employee->load('files');

        return view('admin.employees.files', compact('employee'));
    }

    public function downloadFile(Employee $employee, EmployeeFile $file)
    {
        if ($file->employee_id !== $employee->id) {
            abort(404, 'File not found for this employee');
        }

        if (empty($file->path)) {
            abort(404, 'File path is missing');
        }

        $filePath = storage_path('app/public/' . $file->path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found: ' . $filePath);
        }

        return response()->download($filePath, $file->original_name);
    }

    public function destroyFile(EmployeeFile $file)
    {
        if (!$file) {
            abort(404, 'File not found');
        }

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $employeeId = $file->employee_id;

        $file->delete();

        return redirect()->route('admins.employees.files', $employeeId)
            ->with('success', __('File deleted successfully'));
    }

    public function uploadFile(Request $request, Employee $employee)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $file = $request->file('file');
            $fileType = $file->getClientOriginalExtension();


            $filename = Str::random(20) . '_' . time() . '.' . $fileType;
            $filePath = $file->storeAs('employee_files', $filename, 'public');


            EmployeeFile::create([
                'employee_id' => $employee->id,
                'path' => $filePath,
            ]);

            return redirect()->route('admins.employees.files', $employee->id)
                ->with('success', __('File uploaded successfully'));

        } catch (Exception $e) {
            return redirect()->route('admins.employees.files', $employee->id)
                ->with('error', __('Failed to upload file: ') . $e->getMessage());
        }
    }


    public function exportEmployeeProfileDetailsAsPDF(int $id)
    {
        try {
            $employee = Employee::with([
                'company',
                'iqamaType',
                'files',
                'employeeStages.stage',
                'employeeStages.files',
                'employeeStages',
                'employeeStages.transactions',
                'employeeStages.walletTransaction',
            ])->findOrFail($id);


            $employee->total_paid = $employee->employeeStages->sum('price_amount');
            $employee->total_due = $employee->total_price - $employee->total_paid;


            foreach ($employee->employeeStages as $stage) {
                $stage->payment_data = $this->getStagePaymentData($stage);
            }

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'xbriyaz',
                'direction' => 'rtl',
                'margin_top' => 15,
                'margin_bottom' => 15,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);

            $mpdf->SetTitle('Detailed Employee Report - ' . $employee->name);
            $mpdf->SetAuthor(config('app.name'));

            $html = View::make('admins.employees.pdf.employee-details', compact('employee'))->render();
            $mpdf->WriteHTML($html);

            return response($mpdf->Output('employee-detailed-report-' . $employee->id . '.pdf', 'I'))
                ->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    private function getStagePaymentData($employeeStage)
    {
        if ($employeeStage->walletTransaction) {
            $walletTransaction = $employeeStage->walletTransaction;
            return [
                'amount' => $walletTransaction->amount,
                'status' => $walletTransaction->status,
                'paid_at' => $walletTransaction->completed_at,
                'payment_method' => $walletTransaction->payment_method ?? 'wallet',
                'reference_number' => $walletTransaction->payment_id,
                'notes' => $walletTransaction->description
            ];
        }

        return [
            'amount' => $employeeStage->price_amount ?? 0,
            'status' => $employeeStage->payment_status ?? 'pending',
            'paid_at' => $employeeStage->paid_at,
            'payment_method' => 'direct',
            'reference_number' => null,
            'notes' => null
        ];
    }
}
