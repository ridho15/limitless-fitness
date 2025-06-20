<?php

namespace App\Http\Controllers;

use App\Models\CustomerJadwalStatic;
use App\Models\Kelas;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function kelasJadwal()
    {
        $listInstruktur = User::whereHas('roles', function ($query) {
            $query->where('name', 'Instruktur');
        })->where('status', 1)->get();
        $data['listInstruktur'] = $listInstruktur;
        $listRepetitifTime = CustomerJadwalStatic::where('id_user', Auth::id())
            ->get();
        $data['active'] = 'timetable';
        $data['listRepetitifTime'] = $listRepetitifTime;
        $data['listLocation'] = Location::get();
        return view('base-2.timetable', $data);
    }

    public function kelasList()
    {
        $data['active'] = 'Kelas';
        return view('base-2.class', $data);
    }

    public function detail($id){
        $kelas = Kelas::find($id);
        if(!$kelas){
            return redirect()->back()->with('fail', 'Kelas tidak ditemukan !');
        }
        $data['active'] = 'Kelas';
        $data['kelas'] = $kelas;
        $listKelas = Kelas::where('id_location', session()->get('id_location'))->where('id', '!=', $kelas->id)->get();
        $data['listKelas'] = $listKelas;
        return view('base-2.detail-class', $data);
    }
}
