<?php

namespace App\Http\Livewire\Base;

use App\Models\Kelas as ModelsKelas;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class Kelas extends Component
{
    public $listKelas = [];
    protected $crypt;
    function __construct()
    {
        $this->crypt = new Crypt;
    }
    public function render()
    {
        $this->listKelas = ModelsKelas::where('is_active', 1)
        ->get();
        $data['crypt'] = $this->crypt;
        return view('livewire.base.kelas', $data);
    }
}
