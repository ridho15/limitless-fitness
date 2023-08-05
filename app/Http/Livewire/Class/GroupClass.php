<?php

namespace App\Http\Livewire\Class;

use App\Models\Kelas;
use Livewire\Component;

class GroupClass extends Component
{
    public $listeners = ['changeTypes', 'changeLevel'];
    public $type;

    public $listKelas;
    public $level;
    public function render()
    {
        $this->listKelas = Kelas::where(function ($query) {
            if ($this->type != 0) {
                $query->where('type', $this->type);
            }
        })
            ->get();
        return view('livewire.class.group-class');
    }

    public function mount()
    {
        $this->type = 0;
    }

    public function changeTypes($type)
    {
        $this->type = $type;
    }

    public function changeLevel($level)
    {
        $this->level = $level;
    }
}
