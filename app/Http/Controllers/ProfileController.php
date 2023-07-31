<?php

namespace App\Http\Controllers;

use App\Models\BookingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'profile';
        return view('base-2.profile.index', $data);
    }

    public function bookings()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'bookings';
        return view('base-2.profile.bookings', $data);
    }

    public function packages()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'packages';
        $listPaketExpired = BookingOrder::where('id_user', $user->id)->where('status', 2)->whereDate('expired_date', '<=', now())->get();
        $listPaketAktif = BookingOrder::where('id_user', $user->id)->where(function ($query) {
            $query->where('status', 1)
                ->orWhere('status', 2);
        })->where('is_active', 1)->get();
        $listPaketTidakAktif = BookingOrder::where('id_user', $user->id)
        ->where('is_active', 0)
        ->get();
        $data['listPaketExpired'] = $listPaketExpired;
        $data['listPaketAktif'] = $listPaketAktif;
        $data['listPaketTidakAktif'] = $listPaketTidakAktif;
        return view('base-2.profile.packages', $data);
    }

    public function memberships()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'memberships';
        return view('base-2.profile.memberships', $data);
    }

    public function billing()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'billing';
        return view('base-2.profile.billing', $data);
    }

    public function notificationSettings()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'notification-settings';
        return view('base-2.profile.notification-settings', $data);
    }

    public function waiverForm()
    {
        $user = Auth::user();
        $data['user'] = $user;
        $data['active'] = 'waiver-form';
        return view('base-2.profile.waiver-form', $data);
    }
}
