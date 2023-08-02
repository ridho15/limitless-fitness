<?php

namespace App\Http\Livewire\Class;

use App\Models\Kelas;
use Livewire\Component;

class PrivateClass extends Component
{
    public $listeners = ['changeTypes', 'changeLevel'];

    public $listKelas;
    public $type;
    public $level;

    public function render()
    {
        $this->listKelas = Kelas::where(function ($query) {
            $query->where('category', 1)
                ->orWhere('category', 3)
                ->orWhere('category', 4);
        })->where(function ($query) {
            if ($this->type != 0) {
                $query->where('type', $this->type);
            }
        })->where(function ($query) {
            if ($this->level != 0) {
                $query->where('level', $this->level);
            }
        })
            ->get();
        return view('livewire.class.private-class');
    }

    public function mount()
    {
        $this->type = 0;
    }

    public function changeTypes($type)
    {
        $this->type = $type;
    }

    public function changeLevel($level){
        $this->level = $level;
    }
}
