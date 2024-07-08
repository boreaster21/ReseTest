<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Shop;
use App\Models\Booking;
use App\Models\Fav;
use App\Models\Keyword;
use Carbon\Carbon;

class FavController extends Controller
{
// only()の引数内のメソッドはログイン時のみ有効
  public function __construct()
  {
    $this->middleware(['auth', 'verified'])->only(['fav', 'unfav']);
  }

 /**
  * 引数のIDに紐づくリプライにfavする
  *
  * @param $id リプライID
  * @return \Illuminate\Http\RedirectResponse
  */
  public function fav($id)
  {
    Fav::create([
      'shop_id' => $id,
      'user_id' => Auth::id(),
    ]);

    session()->flash('success', 'You faved the shop.');

    return redirect()->back();
  }

  /**
   * 引数のIDに紐づくリプライにUNfavする
   *
   * @param $id リプライID
   * @return \Illuminate\Http\RedirectResponse
   */
  public function unfav($id)
  {
    $fav = Fav::where('shop_id', $id)->where('user_id', Auth::id())->first();
    $fav->delete();

    session()->flash('success', 'You Unfaved the shop.');

    return redirect()->back();
  }
}
