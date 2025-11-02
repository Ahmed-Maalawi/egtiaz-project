<?php

namespace App\Livewire;

use App\Models\EmployeeStage;
use App\Models\User;
use Livewire\Component;


class ShowEmployeeStages extends Component
{
    public $types;

    public $employeeStages = [];

    public $selectedEmployee = '';

    public function loadEmployeeStages()
    {
        $this->employeeStages = EmployeeStage::with(['stage', 'employee.company','doneBy'])->current()->where('employee_id', $this->selectedEmployee)->get();
    }

//     public function dehydrate()
//     {
//         $this->dispatchBrowserEvent('re-render_select2');
//     }

    public function render()
    {
        $user = User::findOrFail(auth()->id());

        $companies = $user->companyOfModeration;

        return view('livewire.show-employee-stages', compact('companies'));
    }
}
