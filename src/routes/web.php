<?php

use App\Http\Controllers\FortifyController;
use App\Http\Controllers\ReseController;
use App\Http\Controllers\MailSendController;
use App\Http\Controllers\FavController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\MultiAuthController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/thanks', [ReseController::class, 'thx4regi'])->name('thanks');

Route::get('/', [ReseController::class, 'allshop']);
Route::post('/', [ReseController::class, 'allshop']);

Route::get('/detail/{id}', [ReseController::class, 'detail']);

Route::get('/mypage', [ReseController::class, 'mypage'])->middleware('verified');


Route::post('/book', [BookingController::class, 'book'])->middleware('verified');
Route::post('/book/edit', [BookingController::class, 'edit'])->name('booking.edit')->middleware('verified');
Route::post('mypage/review/edit', [ReviewController::class, 'edit'])->name('review.edit')->middleware('verified');

Route::post('/mypage/visited', [BookingController::class, 'visited'])->name('booking.visited')->middleware('verified');
Route::get('/mypage/unbook/{id}', [BookingController::class, 'unbook'])->name('booking.unbook')->middleware('verified');
Route::post('/mypage/posted',[ReviewController::class, 'review'])->name('shop.review')->middleware('verified');

Route::get('/shop/fav/{id}', [FavController::class, 'fav'])->name('shop.fav')->middleware('verified');
Route::get('/shop/unfav/{id}', [FavController::class, 'unfav'])->name('shop.unfav')->middleware('verified');

Route::get('/mypage/payment/index', [StripeController::class, 'payment_index'])->name('payment.index')->middleware('verified');
Route::post('/mypage/payment', [StripeController::class, 'payment'])->name('payment')->middleware('verified');
Route::get('/mypage/payment/complete', [StripeController::class, 'complete'])->name('complete')->middleware('verified');

Route::get('/mypage/qrcode', )->name('qrcode')->middleware('verified');


Route::group(['middleware' => ['auth', 'can:admin']], function () {
    Route::get('/admin', [ReseController::class, 'admin'])->name('admin');
    Route::post('/admin', [ReseController::class, 'regiowner']);
    Route::post('/mail', [MailSendController::class, 'send'])->middleware('verified');
});

Route::group(['middleware' => ['auth', 'can:owner']], function () {
    Route::get('/owner', [ReseController::class, 'owner']);
    Route::post('/owner', [ReseController::class, 'regishop']);
    Route::get('/owner/edit/{id}', [ReseController::class, 'editshop']);
    Route::post('/owner/edit/', [ReseController::class, 'editedshop']);
    Route::post('/mail', [MailSendController::class, 'send'])->middleware('verified');
});





Route::get('/reset-password', function () {
    return view('auth.forgot-password');}
)->name('password.email');

// Route::get('/OwnerRequest', [ReseController::class, 'OwnerRequest'])->name('OwnerRequest')->middleware('verified');






Route::middleware(['auth:sanctum', config('jetstream.auth_session'),'verified'])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
});
