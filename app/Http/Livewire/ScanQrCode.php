<?php

namespace App\Http\Livewire;

use App\Models\OrderPaket;
use App\Models\User;
use chillerlan\QRCode\QRCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;

class ScanQrCode extends Component
{
    public $image;
    public $listeners = ['scanImage', 'setImage'];
    public function render()
    {
        $this->dispatchBrowserEvent('contentChange');
        return view('livewire.scan-qr-code');
    }

    public function scanImage()
    {
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
            if(!$user){
                return $this->emit('hasilScan', false, "Data user tidak ditemukan !");
            }
            $orderPaket = OrderPaket::where('id_user', $user->id)
            ->where('status', 1)
            ->where('is_active', 1)
            ->whereDate('expired_date', '>=', now())
            ->where('id_location', Auth::user()->id_location)
            ->first();

            if(!$orderPaket){
                return $this->emit('hasilScan', false, "Data pekat tidak ditemukan !");
            }

            return $this->emit('hasilScan', true, "Data valid");
        } catch (\Throwable $th) {
         $this->emit('hasilScan', false, "Hasil tidak valid !. " . $th->getMessage());   
        }

    }
}
