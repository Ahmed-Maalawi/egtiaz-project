<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalletChargeSuccessMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $moderator;
    public $transaction;
    public $company;

    /**
     * Create a new message instance.
     */
    public function __construct(User $moderator, WalletTransaction $transaction, Company $company)
    {
        $this->moderator = $moderator;
        $this->transaction = $transaction;
        $this->company = $company;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Payment Received - Company Wallet Charged Successfully')
            ->view('emails.wallet-charged-success')
            ->with([
                'moderatorName' => $this->moderator->name,
                'companyName' => $this->company->name,
                'amount' => $this->transaction->amount,
                'currency' => $this->transaction->currency,
                'transactionId' => $this->transaction->id,
                'paymentDate' => $this->transaction->completed_at,
                'newBalance' => $this->transaction->wallet->balance,
            ]);
    }
}
