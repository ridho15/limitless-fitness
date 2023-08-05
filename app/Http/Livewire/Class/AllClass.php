<?php

namespace App\Http\Livewire\Class;

use App\Models\Kelas;
use Livewire\Component;

class AllClass extends Component
{
    public $listeners = ['changeTypes', 'changeLevel'];
    public $listKelas;
    public $type;
    public $level;
    public function render()
    {
        $this->listKelas = Kelas::where(function ($query) {
            if ($this->type != 0) {
                $query->where('type', $this->type);
            }
        })
            ->get();
        return view('livewire.class.all-class');
    }

    public function mount()
    {
        $this->type = 0;
        $this->level = 0;
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
