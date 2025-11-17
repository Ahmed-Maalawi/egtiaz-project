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

    public function __construct(Employee $employee, User $moderator)
    {
        $this->employee = $employee;
        $this->moderator = $moderator;
    }

    public function build()
    {
        return $this->subject('All Papers Completed - ' . $this->employee->name)
            ->markdown('emails.employee-papers-completed')
            ->with([
                'moderatorName' => $this->moderator->name,
                'employeeName'  => $this->employee->name,
                'companyName'   => $this->employee->company->name,
                'completedStages' => $this->employee->stages,
                'totalStages'   => $this->employee->stages->count(),
                'completedAt'   => now()->format('d M Y, h:i A'),
                'employee'      => $this->employee,
            ]);
    }

}
