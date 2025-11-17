<?php

namespace App\Jobs;

use App\Mail\EmployeePapersCompletedMail;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmployeeCompletionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $employee;
    public $moderator;

    public $tries = 3;
    public $timeout = 180;

    public function __construct(Employee $employee, User $moderator)
    {
        $this->employee = $employee;
        $this->moderator = $moderator;

        // Enable after-commit dispatch safely
        $this->afterCommit();
    }

    public function handle(): void
    {
        try {
            Mail::to($this->moderator->email)
                ->send(new EmployeePapersCompletedMail($this->employee, $this->moderator));

            Log::info("Email sent successfully", [
                'employee_id' => $this->employee->id,
                'moderator_id' => $this->moderator->id,
                'email' => $this->moderator->email,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send completion email", [
                'employee_id' => $this->employee->id,
                'moderator_id' => $this->moderator->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("SendEmployeeCompletionEmail failed permanently", [
            'employee_id' => $this->employee->id,
            'moderator_id' => $this->moderator->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
