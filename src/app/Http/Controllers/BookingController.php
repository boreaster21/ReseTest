<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fav;
use App\Models\User;
use App\Models\Shop;
use App\Models\ShopReview;
use App\Models\Booking;
use App\Models\Keyword;
use Carbon\Carbon;
use App\Http\Requests\BookingRequest;

class BookingController extends Controller
{

    public function book(BookingRequest $Request)
    {
        Booking::create([
            'user_id' => Auth::user()->id,
            'shop_id' => $Request->shop_id,
            'owner_id' => $Request->owner_id,
            'date' => $Request->date,
            'time' => $Request->time,
            'num' => $Request->num,
            'visited' => 0,
        ]);
        return view('thx4booking');
    }

    public function unbook($id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->first();
        $booking->delete();

        return redirect()->back();
    }
    public function edit(REQUEST $Request)
    {
        $target = Booking::where('id', $Request->booking_id)->first();
        $target->update([
            'date' => $Request->new_date,
            'time' => $Request->new_time,
            'num' => $Request->new_num,

        ]);
        $user = Auth::user();
        $favs = Fav::where('user_id', $user->id)->orderBy('shop_id', 'asc')->get();
        $shops = Shop::get();
        $bookings = Booking::where('user_id', $user->id)->get();
        $booking_No = 1;
        $No = 1;
        $reviews = ShopReview::where('user_id', $user->id)->get();
        return view('mypage', compact('shops', 'bookings', 'booking_No', 'No', 'favs', 'reviews'));
    }
    public function visited(REQUEST $Request)
    {
        $target = Booking::where('id', $Request->booking_id)->first();
        $target->update([
            'visited' => 1,
        ]);
        $user = Auth::user();
        $favs = Fav::where('user_id', $user->id)->orderBy('shop_id', 'asc')->get();
        $shops = Shop::get();
        $bookings = Booking::where('user_id', $user->id)->get();
        $booking_No = 1;
        $No = 1;
        $reviews = ShopReview::where('user_id', $user->id)->get();
        return view('mypage', compact('shops', 'bookings', 'booking_No', 'No', 'favs', 'reviews'));
    }
}
