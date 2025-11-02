<?php

namespace App\Livewire;

use App\Models\Stage;
use Livewire\Component;
use Livewire\WithPagination;

class IqamaStages extends Component
{
    use WithPagination;

    public $selectType = '';
    public $types;

    public function updatingSelectType()
    {
        // reset to page 1 whenever filter changes
        $this->resetPage();
    }

    public function render()
    {
        $stages = [];

        if (!empty($this->selectType)) {
            $stages = Stage::where('iqama_type_id', $this->selectType)
                ->orderBy('order')
                ->paginate(10);
        }

        // 🔍 debug if needed
        // dd($this->selectType, $this->types, $stages);

        return view('livewire.iqama-stages', [
            'stages' => $stages
        ]);
    }
}
