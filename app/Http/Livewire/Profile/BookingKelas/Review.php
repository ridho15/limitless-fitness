<?php

namespace App\Http\Livewire\Profile\BookingKelas;

use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Review extends Component
{
    public $listeners = ['simpanReview', 'setBookingKelas', 'setRating'];
    public $rating;
    public $review;
    public $id_booking_kelas;
    public function render()
    {
        return view('livewire.profile.booking-kelas.review');
    }

    public function mount()
    {
    }

    public function setBookingKelas($id_booking_kelas)
    {
        $this->id_booking_kelas = $id_booking_kelas;
        $bookingKelas = BookingKelas::find($this->id_booking_kelas);
        $this->review = $bookingKelas->review;
        $this->rating = $bookingKelas->rating;
    }

    public function simpanReview()
    {
        $this->validate([
            'rating' => 'required|numeric',
            'review' => 'required|string',
            'id_booking_kelas' => 'required|numeric',
        ], [
            'rating.required' => 'Rating belum dipilih',
            'rating.numeric' => 'Rating tidak valid !',
            'review.required' => 'Review Kelas belum diisi',
            'review.string' => 'Review tidak valid !',
            'id_booking_kelas.required' => 'Booking kelas belum dipilih',
            'id_booking_kelas.numeric' => 'Booking kelas tidak valid !'
        ]);

        $bookingKelas = BookingKelas::find($this->id_booking_kelas);
        if (!$bookingKelas) {
            return session()->flash('fail', 'Booking kelas tidak valid !');
        }

        $bookingKelas->update([
            'review' => $this->review,
            'rating' => $this->rating
        ]);

        // Berikan rating ke kelas activity
        $kelasActivity = $bookingKelas->kelasActivity;
        $jumlahRating = 0;
        $jumlahReview = $kelasActivity->bookingKelas->where('rating', '!=', null)->count();
        foreach ($kelasActivity->bookingKelas->where('rating', '!=', null) as $bookingKelas) {
            $jumlahRating += $bookingKelas->rating;
        }
        if ($jumlahRating == 0 || $jumlahReview == 0) {
            $total = 0;
        } else {
            $total = ($jumlahRating / $jumlahReview) * 2;
        }

        $kelasActivity->update([
            'rating' => $total
        ]);

        // Berikan rating ke instruktur
        $jumlahRating = 0;
        $jumlahReview = 0;
        $instruktur = User::find($kelasActivity->id_user);
        if (!$instruktur) {
            return session()->flash('fail', 'Data Instruktur tidak ditemukan!');
        }
        $listKelasActivity = KelasActivity::where('id_user', $instruktur->id)
            ->where('rating', '!=', null)
            ->select(['rating'])
            ->get();
        $jumlahReview = $listKelasActivity->count();
        foreach ($listKelasActivity as $kelasActivity) {
            $jumlahRating += $kelasActivity->rating;
        }

        if ($jumlahRating == 0 || $jumlahReview == 0) {
            $total = 0;
        } else {
            $total = $jumlahRating / $jumlahReview;
        }
        $instruktur->update([
            'rating' => $total
        ]);

        $this->review = null;
        $this->rating = null;
        activity()->causedBy(Auth::user())
            ->log('Melakukan review kelas');
        session()->flash('success', 'Terimakasih sudah memberikan rating dan review kelas');

        return $this->emit('finishReview', true, 'Terimakasih sudah memberikan rating dan review kelas');
    }

    public function setRating($rating)
    {
        $this->rating = $rating + 1;
    }
}
