<?php

namespace App\Http\Livewire\Class;

use App\Models\Kelas;
use Livewire\Component;

class AllClass extends Component
{
    public $listeners = ['changeTypes', 'changeLevel', 'refreshClass' => '$refresh'];
    public $listKelas;
    public $type;
    public $level;
    public function render()
    {
        $this->listKelas = Kelas::where(function ($query) {
            $query->where('id_location', session()->get('id_location'))
                ->orWhere('id_location', 0);
        })->where('is_active', 1)->get();
        $this->dispatchBrowserEvent('contentChange');
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
