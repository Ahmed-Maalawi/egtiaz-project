<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeePapersCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $moderator;
    public $completedStages;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee, User $moderator)
    {
        $this->employee = $employee;
        $this->moderator = $moderator;

        // Load completed stages only
        $this->employee->load(['stages' => function ($query) {
            $query->where('status', 'completed')->with('stage');
        }, 'company']);

        $this->completedStages = $this->employee->stages;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('All Papers Completed - ' . $this->employee->name)
            ->markdown('emails.employee-papers-completed')
            ->with([
                'moderatorName' => $this->moderator->name,
                'employeeName' => $this->employee->name,
                'companyName' => $this->employee->company->name,
                'completedStages' => $this->completedStages,
                'totalStages' => $this->completedStages->count(),
                'completedAt' => now()->format('d M Y, h:i A'),
                'employee' => $this->employee,
            ]);
    }

}
