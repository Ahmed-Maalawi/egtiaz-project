<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PassportExpiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $moderator;
    public $employee;
    public $daysUntilExpiry;

    public function __construct(User $moderator, Employee $employee)
    {
        $this->moderator = $moderator;
        $this->employee = $employee;
        $this->daysUntilExpiry = Carbon::now()->diffInDays(Carbon::parse($employee->passport_expiry_date));
    }

    public function build()
    {
        return $this->subject('⚠️ Passport Expiry Alert - ' . $this->employee->name)
            ->view('admin.emails.passport-expiry')
            ->with([
                'moderatorName' => $this->moderator->name,
                'employeeName' => $this->employee->name,
                'passportNumber' => $this->employee->passport_number,
                'expiryDate' => Carbon::parse($this->employee->passport_expiry_date)->format('F j, Y'),
                'daysUntilExpiry' => $this->daysUntilExpiry,
                'companyName' => $this->employee->company->name,
            ]);
    }
}
