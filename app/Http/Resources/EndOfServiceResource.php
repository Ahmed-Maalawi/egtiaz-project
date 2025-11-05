<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EndOfServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'                    => $this->id,
            'joining_date'          => Carbon::parse($this->joining_date)->format('Y-m-d'),
            'leaving_date'          => Carbon::parse($this->leaving_date)->format('Y-m-d'),
            'basic_salary'          => (float) $this->basic_salary,
            'gross_salary'          => (float) $this->gross_salary,
            'incentive'             => (float) $this->incentive,
            'rewards'               => (float) $this->rewards,
            'other_additions'       => (float) $this->other_additions,
            'cash_advance'          => (float) $this->cash_advance,
            'petty_cash'            => (float) $this->petty_cash,
            'fines'                 => (float) $this->fines,
            'compensation_notice'   => (float) $this->compensation_notice,
            'other_deductions'      => (float) $this->other_deductions,
            'annual_leave_balance'  => (float) $this->annual_leave_balance,
            'net_pay'               => (float) $this->net_pay,
            'notice_period_days'    => $this->notice_period_days,
            'employee'              => $this->whenLoaded('employee')
        ];
    }
}

