<?php

namespace App\Console\Commands;

use App\Mail\PassportExpiryMail;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckPassportExpiry extends Command
{
    protected $signature = 'check:passport-expiry';
    protected $description = 'Check passport expiry and send notifications to moderators';

    public function handle()
    {
        $this->info('Checking passport expiry dates...');

        $oneMonthFromNow = Carbon::now()->addMonth();

        // Debug: Show the exact date we're searching for
        $this->info("Looking for passports expiring on: " . $oneMonthFromNow->toDateString());

        // Debug: Show total employees
        $totalEmployees = Employee::count();
        $this->info("Total employees in database: {$totalEmployees}");

        // Debug: Show employees with expired_date
        $employeesWithDates = Employee::whereNotNull('expired_date')->count();
        $this->info("Employees with expired_date set: {$employeesWithDates}");

        $expiringEmployees = Employee::whereDate('expired_date', '=', $oneMonthFromNow->toDateString())
            ->with(['company', 'company.moderators' => function($query) {
                $query->where('status', 'active');
            }])
            ->get();

        $this->info("Found {$expiringEmployees->count()} employees with passports expiring in 1 month.");

        // Debug: Show some sample expiry dates
        $sampleDates = Employee::whereNotNull('expired_date')
            ->orderBy('expired_date')
            ->limit(5)
            ->pluck('expired_date', 'name');

        $this->info("Sample expiry dates:");
        foreach ($sampleDates as $name => $date) {
            $this->info("  - {$name}: {$date}");
        }

        foreach ($expiringEmployees as $employee) {
            try {
                $this->sendPassportExpiryNotification($employee);
                $this->info("Notification sent for employee: {$employee->name}");
            } catch (\Exception $e) {
                Log::error('Failed to send passport expiry notification', [
                    'employee_id' => $employee->id,
                    'error' => $e->getMessage()
                ]);
                $this->error("Failed to send notification for employee: {$employee->name}");
            }
        }

        $this->info('Passport expiry check completed.');
    }

    private function sendPassportExpiryNotification(Employee $employee)
    {
        $company = $employee->company;

        if (!$company) {
            Log::warning('Company not found for employee', ['employee_id' => $employee->id]);
            return;
        }

        $moderators = $company->moderators()->where('status', 'active')->get();

        if ($moderators->isEmpty()) {
            Log::warning('No active moderators found for company', [
                'company_id' => $company->id,
                'employee_id' => $employee->id
            ]);
            return;
        }

        foreach ($moderators as $moderator) {
            try {
                Mail::to($moderator->email)
                    ->sendNow(new PassportExpiryMail($moderator, $employee));

                Log::info('Passport expiry notification queued for moderator', [
                    'moderator_id' => $moderator->id,
                    'moderator_email' => $moderator->email,
                    'employee_id' => $employee->id,
                    'expiry_date' => $employee->expired_date
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to queue passport expiry email for moderator', [
                    'moderator_id' => $moderator->id,
                    'moderator_email' => $moderator->email,
                    'employee_id' => $employee->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
