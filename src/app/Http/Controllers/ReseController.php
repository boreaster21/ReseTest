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
use Carbon\DateTime;
use Illuminate\Support\Facades\Session;


class ReseController extends Controller
{
  public function allshop(REQUEST $request)
  {
    $areas = Shop::groupBy('area')->get('area');
    $ganres = Shop::groupBy('ganre')->get('ganre');
    // $favCounts = Shop::withCount('favs')->orderBy('id', 'desc')->paginate(10);

    //検索+ジャンル+エリア
    if ($request->keyword) {
      if ($request->area) 
      {
        if ($request->ganre) 
        {
          $shops = Shop::KeywordSearch($request->keyword)->areaSearch($request->area)->ganreSearch($request->ganre)->get();
          return view('allshop', compact('shops', 'areas', 'ganres'));//検索+ジャンル+エリア
        }
        else {
          $shops = Shop::KeywordSearch($request->keyword)->areaSearch($request->area)->get();
          return view('allshop', compact('shops', 'areas', 'ganres'));//検索+エリア
        }
      }
      elseif ($request->ganre) 
      {
        $shops = Shop::KeywordSearch($request->keyword)->ganreSearch($request->ganre)->get();
        return view('allshop', compact('shops', 'areas', 'ganres')); //検索+ジャンル
      }
      else
      {
        $shops = Shop::KeywordSearch($request->keyword)->get();
        return view('allshop', compact('shops', 'areas', 'ganres'));//検索
      }
      $shops = Shop::KeywordSearch($request->keyword)->get();
      return view('allshop', compact('shops', 'areas', 'ganres'));
    } 
    elseif ($request->area) 
    {
      if ($request->ganre) 
      {
        $shops = Shop::areaSearch($request->area)->ganreSearch($request->ganre)->get();
        return view('allshop', compact('shops', 'areas', 'ganres')); //ジャンル+エリア
      } 
      else 
      {
        $shops = Shop::areaSearch($request->area)->get();
        return view('allshop', compact('shops', 'areas', 'ganres')); //エリア

      }
    } 
    elseif ($request->ganre)
    {
      $shops = Shop::ganreSearch($request->ganre)->get();
      return view('allshop', compact('shops', 'areas', 'ganres')); //ジャンル
    }
    else
    {
      //無条件
      $shops = Shop::get();
      return view('allshop', compact('shops', 'areas', 'ganres'));
    }
  }

  public function detail($id)
  {
    $shop = Shop::where('id', $id)->first();
    $user = User::where('id', Auth::user()->id)->first();
    return view('detail', compact('shop','user'));
  }

  public function thx4regi()
  {
    return view('thx4regi');
  }

  public function mypage()
  {
    $user = Auth::user();
    $favs = Fav::where('user_id', $user->id)->orderBy('shop_id', 'asc')->get();
    $shops = Shop::get();
    $bookings = Booking::where('user_id', $user->id)->get();
    $booking_No = 1;
    $No = 1;
    $reviews = ShopReview::where('user_id', $user->id)->get();
    // $now = Carbon::now();
    // foreach($bookings as $booking){
    //   if ($booking->date < $now ) { //翌日以降
    //     $booking->update([
    //       'visited' => 1,
    //     ]);
    //   }
    //   elseif($booking->date == $now || $booking->time < $now){//同日で予約時間を超えている
    //     $booking->update([
    //       'visited' => 1,
    //     ]);
    //   };
    // };
    $bookings = Booking::where('user_id', $user->id)->get();
    
    return view('mypage', compact('shops', 'bookings', 'booking_No', 'No','favs', 'reviews'));
  }


  public function admin()
  {
    $users = User::get();
    return view('admin', compact('users'));
  }

  public function regiowner(REQUEST $Request)
  {
    User::where('id', $Request->id)->first()->fill(['role' => $Request->role])->save();
    return
    redirect()->back()
      ->with('message', 'ユーザーの権限を更新しました');
  }

  public function owner()
  {
    $users = User::get();
    $bookings = Booking::get();
    $booking_No = 1;
    
    $shops = Shop::where('user_id', Auth::user()->id)->get();
    return view('owner', compact('shops', 'bookings', 'booking_No', 'users'));
  }

  public function regishop(REQUEST $request)
  {
    
    $path = $request->file('image')->store('public/images');
    
    Shop::create([
      'user_id' => Auth::user()->id,
      'name' => $request->name,
      'area' => $request->area,
      'ganre' => $request->ganre,
      'detail' => $request->detail,
      'URL' => $path,
    ]);
    $shops = Shop::where('user_id', Auth::user()->id)->get();

    return view('owner', compact('shops'));
  }

  public function editshop($id)
  {
    $shop = Shop::where('id', $id)->first();
    return view('editshop', compact('shop'));
  }
  public function editedshop(REQUEST $req)
  {
    // dd($req->file('image'));
    if($req->file('image')){
      $path = $req->file('image')->store('public/images');
      Shop::where('id', $req->shop_id)->first()->fill([
        'name' => $req->name,
        'area' => $req->area,
        'ganre' => $req->ganre,
        'detail' => $req->detail,
        'URL' => $path,
      ])->save();
    }
    else{
      Shop::where('id', $req->shop_id)->first()->fill([
        'name' => $req->name,
        'area' => $req->area,
        'ganre' => $req->ganre,
        'detail' => $req->detail,
      ])->save();
    }

    // $shop = Shop::where('id', $id)->first();
    return redirect()->back()->with('message', '変更を保存しました');
  }

  // public function OwnerRequest()
  // {
  //   User::where('id', '15')->first()->fill(['role' => 100])->save();
  //   return redirect()->back();
  // }
  
  // -----------------------------------------------------

  public function startwork()
  {
    $user = Auth::user();
    $start_work = Carbon::now();
    $today = Carbon::today();
    if (Work::whereDate('work_on', $today)->where('user_id', $user->id)->first()) {
      return redirect()->back()
        ->with('message', '本日すでに出勤打刻を完了しています');
    } elseif (Work::whereDate('work_on', $today)->where('user_id', $user->id)->first() == null) {
      Work::create([
        'start_at' => $start_work,
        'user_id' => $user->id,
        'work_on' => $start_work,
      ]);
      return redirect()->back()
        ->with('message', '出勤打刻が完了しました');
    }
  }

  public function finishwork()
  {
    $user = Auth::user();
    $finish_work = Carbon::now();
    $target = Work::whereDate('start_at', $finish_work)->where('user_id', $user->id)->where('finished_at', null)->first();
    $work_notstarted = Work::whereDate('work_on', $finish_work)->where('user_id', $user->id)->first() == null;
    $alreadyfinished = Work::whereDate('finished_at', $finish_work)->where('user_id', $user->id)->first();

    if ($work_notstarted) 
    {
      return redirect()->back()
        ->with('message', '本日まだ出勤打刻を完了していません');
    } 
    elseif ($alreadyfinished) 
    {
      return redirect()->back()
        ->with('message', '本日すでに退勤打刻を完了しています');
    } 
    elseif (isset($target->start_rest) || isset($target->finished_rest)) 
    {
      
      if ($target->start_rest > $target->finished_rest) {
        $total_work = $target->start_at->diffInSeconds($finish_work);
        $start_rest = $target->start_rest;
        $this_rest = $start_rest->diffInSeconds($finish_work);
        $total_rest = $this_rest + $target->total_rest;

        $target->update([
          'finished_at' => $finish_work,
          'total_work' => $total_work,
          'finished_rest' => $finish_work,
          'total_rest' => $total_rest,
        ]);
        return redirect()->back()
          ->with('message', '休憩を終了し、退勤打刻が完了しました');
      }
      else
      {
        $total_work = $target->start_at->diffInSeconds($finish_work);
        $target->update([
          'finished_at' => $finish_work,
          'total_work' => $total_work,
        ]);
        return redirect()->back()
        ->with('message', '退勤打刻が完了しました');

      }
    } 
    elseif ($target) 
    {
      $total_work = $target->start_at->diffInSeconds($finish_work);
      $target->update([
        'finished_at' => $finish_work,
        'total_work' => $total_work,
      ]);
      return redirect()->back()
        ->with('message', '退勤打刻が完了しました');
    }
    else{
      return redirect()->back()
      ->with('message', 'エラーが発生しました');;
    }
  }

  public function startrest()
  {
    $user = Auth::user();
    $start_rest = Carbon::now();
    $target = Work::whereDate('start_at', $start_rest)->where('user_id', $user->id)->where('finished_at', null)->where('start_rest', null)->where('finished_rest', null)->first();
    $work_notstarted = Work::whereDate('work_on', $start_rest)->where('user_id', $user->id)->first() == null;
    $work_alreadyfinished = Work::whereDate('finished_at', $start_rest)->where('user_id', $user->id)->first();
    $rest_alreadystarted = Work::whereDate('start_rest', $start_rest)->where('user_id', $user->id)->where('finished_rest', null)->first();
    $rest_another = Work::whereDate('finished_rest', $start_rest)->where('user_id', $user->id)->where('finished_at', null)->first();
    $overnight = Work::whereDate('start_at', !$start_rest)->where('user_id', $user->id)->where('finished_at', null)->first();
    if ($work_notstarted) 
    {
      dd('a');
      return redirect()->back()
        ->with('message', '本日まだ出勤打刻を完了していません');
    } 
    elseif ($work_alreadyfinished) 
    {
      dd('b');
      return redirect()->back()
        ->with('message', '本日すでに退勤打刻を完了しています');
    } 
    elseif($overnight)
    {

    }
    elseif ($target) 
    {
      $target->update([
        'start_rest' => $start_rest,
      ]);
      return redirect()->back()
        ->with('message', '休憩を開始しました');
    }
    elseif ($rest_alreadystarted) 
    {
      return redirect()->back()
        ->with('message', 'すでに休憩を開始しています');
    } 
    elseif($rest_another){
      if ($rest_another->start_rest > $rest_another->finished_rest) 
      {
        return redirect()->back()
          ->with('message', 'すでに休憩を開始しています');
      } 
      elseif ($rest_another->start_rest < $rest_another->finished_rest) 
      {
        $rest_another->update([
          'start_rest' => $start_rest,
        ]);
        return redirect()->back()
          ->with('message', '休憩を再び開始しました');
      }
    }
    else
    {
      return redirect()->back()
        ->with('message', '予期せぬエラーが発生しました');
    }
  }

  public function finishrest()
  {
    $user = Auth::user();
    $finish_rest = Carbon::now();
    $target = Work::whereDate('start_at', $finish_rest)->where('user_id', $user->id)->where('finished_at', null)->first();
    $work_notstarted = Work::whereDate('work_on', $finish_rest)->where('user_id', $user->id)->first() == null;
    $work_alreadyfinished = Work::whereDate('finished_at', $finish_rest)->where('user_id', $user->id)->first();

    if ($work_notstarted) {
      return redirect()->back()
        ->with('message', '本日まだ出勤打刻を完了していません');
    } elseif ($work_alreadyfinished) {
      return redirect()->back()
        ->with('message', '本日すでに退勤打刻を完了しています');
    } elseif (empty($target['start_rest']) || $target->start_rest < $target->finished_rest) {
      return redirect()->back()
        ->with('message', '休憩が開始されていません');
    } elseif ($target || $target->start_rest > $target->finished_rest) {
      $this_rest = $target->start_rest->diffInSeconds($finish_rest);
      $total_rest = $this_rest + $target->total_rest;
      $target->update([
        'finished_rest' => $finish_rest,
        'total_rest' => $total_rest,
      ]);
      return redirect()->back()
        ->with('message', '休憩を終了しました');
    }
  }

  public function showdate(REQUEST $Request)
  {
    // 前日ボタン押したら
    if ($Request->before == "before") {
      $users = User::get();
      $last_day = date('Y-m-d', strtotime($Request->day . '-1 day'));
      $dates = Work::latest('finished_at')->where('work_on', $last_day)->paginate(5);
      $day = $last_day;
      Date::first()->update([
        'target' => $last_day,
      ]);

      return view('date', compact('users', 'dates', 'day'))->with('message', 'POSTあり');
    }
    // 翌日ボタン押したら
    elseif ($Request->after == "after") {
      $users = User::get();
      $next_day = date('Y-m-d', strtotime($Request->day . '1 day'));
      $dates = Work::latest('finished_at')->where('work_on', $next_day)->paginate(5);
      $day = $next_day;
      Date::first()->update([
        'target' => $next_day,
      ]);

      return view('date', compact('users', 'dates', 'day'))->with('message', 'POSTなし');
    }
    // ページリクエストがあったら ※ページネーションのリロード対策
    elseif ($Request->page) {
      $users = User::get();
      $day = Date::first()->target;
      $dates = Work::latest('finished_at')->where('work_on', $day)->paginate(5);

      return view('date', compact('users', 'dates', 'day'))->with('message', '今日');
    }
    // ページリクエストがなかったら　//一発目/dateに来た時
    elseif ($Request->page == null) {
      $users = User::get();
      $day = Carbon::today()->format('Y-m-d');
      //ページめくった後に戻ってきたパターン
      if (Date::first()) {
        Date::first()->update([
          'target' => $day,
        ]);
      }
      //初めて/dateに来たパターン（テーブルにデータがない）
      elseif (Date::first() == null) {
        Date::create([
          'target' => $day,
        ]);
      }
      $dates = Work::latest('finished_at')->where('work_on', $day)->paginate(5);
      return view('date', compact('users', 'dates', 'day'))->with('message', '今日');
    }
  }
  public function users(REQUEST $request)
  {
    
    //検索した場合
    if($request->keyword)
    {
      $users = User::KeywordSearch($request->keyword)->paginate(5);
      //
      if(empty(Keyword::first())){
        Keyword::create([
          'keyword' => $request->keyword,
        ]);
      }
      else{
        Keyword::first()->update([
          'keyword' => $request->keyword,
        ]);
      }
      
      return view('users', compact('users'));
    } 
    //ページネーションクリック場合
    elseif ($request->page) 
    {
      $keyword = Keyword::first()->keyword;
      $users = User::KeywordSearch($keyword)->paginate(5);
      return view('users', compact('users'));
    }
    //初めて/usersに来たパターン
    else 
    {
      $users = User::paginate(5);
      return view('users', compact('users'));
    }
  }

  public function parsonal(REQUEST $request)
  {

    if ($request->serch) {
      $user_id = $request->user_id;
      $user_name = $request->user_name;
      $records = work::where('user_id', $request->user_id)->paginate(5);
      return view('parsonal', compact('records', 'user_name', 'user_id'));
    }
    elseif ($request->year) {
      $user_id = $request->user_id;
      $user_name = $request->user_name;
      $target_date = Carbon::create($request->year, $request->month, 1, null, null, null);
      $records= work::where('user_id', $request->user_id)->whereMonth('start_at', $target_date)->paginate(5);
      $year = $request->year;
      $month = $request->month;
      return view('parsonal', compact('records', 'user_name', 'user_id', 'year', 'month'));
    }
    else{
      $user_id = $request->user_id;
      $user_name = $request->user_name;
      $records = work::where('user_id', Auth::user()->id)->paginate(5);
      return view('parsonal', compact('records', 'user_name', 'user_id',));
    }
  }
}
