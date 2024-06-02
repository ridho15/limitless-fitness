<?php

namespace App\Http\Livewire;

use App\Filament\Resources\CheckInOutResource;
use App\Models\CheckInOut;
use App\Models\OrderPaket;
use App\Models\User;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;

class ScanQrCode extends Component
{
    public $image;
    public $user;
    public $orderPaket;
    public $listeners = ['scanImage', 'setImage', 'checkBarcode', 'setNoCamera'];
    public $user_code;
    public $no_camera = true;
    public function render()
    {
        $this->dispatchBrowserEvent('contentChange');
        return view('livewire.scan-qr-code');
    }

    public function scanImage()
    {
        if ($this->user == null) {
            $this->user_code = null;
            return $this->emit('hasilScan', false, "Data customer tidak ditemukan");
        }

        $checkInOut = CheckInOut::where('id_user', $this->user->id)
            ->whereDate('jadwal', now())
            ->orderBy('jadwal', 'DESC')
            ->first();

        $data['id_user'] = $this->user->id;
        $data['id_order_paket'] = $this->orderPaket != null ? $this->orderPaket->id : null;
        $data['jadwal'] = now();
        $data['id_location'] = Auth::user()->id_location;
        if (!$checkInOut) {
            $data['status'] = 1;
        } elseif ($checkInOut->status == 1) {
            $menit = Carbon::parse($checkInOut->jadwal)->diffInMinutes(now());
            if ($menit <= 5) {
                $this->user_code = null;
                return $this->emit('hasilScan', false, "Customer baru saja masuk. tunggu 5 menit sebelum keluar");
            }
        } else {
            $data['status'] = 1;
        }

        CheckInOut::create($data);
        $this->emit('hasilScan', true, "Data berhasil disimpan");
        set_time_limit(2);
        return redirect(CheckInOutResource::getUrl('index'));
    }

    public function setNoCamera($no_camera){
        $this->no_camera = $no_camera;
    }

    public function checkBarcode()
    {
        try {
            if (strlen($this->user_code) > 8) {
                $this->user_code = Crypt::decrypt($this->user_code);
            }
            $this->user = User::find($this->user_code);
            if ($this->user == null) {
                $this->user_code = null;
                return $this->emit('hasilScan', false, "Data user tidak ditemukan !");
            }

            $checkIn = CheckInOut::where('id_user', $this->user->id)->first();

            $orderPaket = OrderPaket::where('id_user', $this->user->id)
                ->where('status', 1)
                ->where('is_active', 1)
                ->whereDate('expired_date', '>=', now())
                ->where('id_location', Auth::user()->id_location)
                ->first();

            if (!$orderPaket && $checkIn) {
                $this->user_code = null;
                return $this->emit('hasilScan', false, "Data Paket tidak ditemukan !");
            }

            $this->orderPaket = $orderPaket;
            return $this->emit('showModal');
        } catch (\Throwable $th) {
            $this->user_code = null;
            $this->emit('hasilScan', false, "Hasil tidak valid !. " . $th->getMessage());
        }
    }

    public function setImage($image)
    {
        $this->image = $image;
        $folder = 'public/qrcode/';
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10) . '.' . 'jpeg';
        Storage::put($folder . $imageName, base64_decode($image));
        $folder = str_replace('public/', 'storage/', $folder);
        $file = $folder . $imageName;
        try {
            $result = (new QRCode())->readFromFile(public_path($file));
            $content = $result->data;
            $matrix = $result->getQRMatrix();
            $id_user = Crypt::decrypt($content);
            $user = User::find($id_user);
            if (!$user) {
                return $this->emit('hasilScan', false, "Data user tidak ditemukan !");
            }

            $checkIn = CheckInOut::where('id_user', $user->id)->first();

            $orderPaket = OrderPaket::where('id_user', $user->id)
                ->where('status', 1)
                ->where('is_active', 1)
                ->whereDate('expired_date', '>=', now())
                ->where('id_location', Auth::user()->id_location)
                ->first();

            if (!$orderPaket && $checkIn) {
                $this->user_code = null;
                return $this->emit('hasilScan', false, "Data Paket tidak ditemukan !");
            }

            $this->user = $user;
            $this->orderPaket = $orderPaket;
            return $this->emit('showModal');
            // return $this->emit('hasilScan', true, "Berhasil Melakukan Scan QR Code");
        } catch (\Throwable $th) {
            $this->user_code = null;
            $this->emit('hasilScan', false, "Hasil tidak valid !. " . $th->getMessage());
        }
    }
}
