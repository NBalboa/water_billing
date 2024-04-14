<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\RegisterController;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\AdminOrCollector;
use App\Models\Billing;
use App\Models\Consumer;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
})->name('login');

// TODOS

// user edit by admin or owner
// user delete only admin
// change password by admin or owner


// Route::get('login', [LoginController::class, 'create'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->middleware('guest');

Route::middleware([AdminOrCollector::class, 'auth'])->group(function () {

    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('profile/{user_id}', [UserController::class, 'profile']);
    Route::post('profile/new/password/{user_id}', [UserController::class, 'changePassword']);
    Route::post('profile/new/username/{user_id}', [UserController::class, 'changeUsername']);
    Route::get('home', function () {
        $total_consumer = Consumer::count();
        $total_paid = Billing::where('status', "PAID")->count();
        $total_pending = Billing::where('status', "PENDING")->count();

        $currentDate = Carbon::now()->setTimezone('Asia/Manila')->toDateString();
        $currentMonth = Carbon::now()->setTimezone('Asia/Manila')->startOfMonth();
        $daily_sales =
            Billing::where('status', "PAID")
            ->whereDate('paid_at', $currentDate)
            ->sum('total');

        $monthly_sales =
            Billing::where('status', "PAID")
            ->whereMonth('paid_at', $currentMonth)
            ->sum('total');
        // ->;

        return view('home', [
            'total_consumer' => $total_consumer,
            'total_paid' => $total_paid,
            'total_pending' => $total_pending,
            'daily_sales' => $daily_sales,
            'monthly_sales' => $monthly_sales
        ]);
    });
    Route::get('billing/invoice', [BillingController::class, 'invoices']);
    Route::get('billing/{billing_id}', [BillingController::class, 'billing']);

    Route::post('billing/pay/{billing_id}', [BillingController::class, 'pay']);

    Route::get('billing/print/{billing_id}', [BillingController::class, 'print']);
    Route::get('billings/print/receipts/{month}/{year}/{status}/{search}', [BillingController::class, 'printAll']);
    Route::get('all/billings', [BillingController::class, 'all']);
    Route::get('all/transactions', [TransactionController::class, 'all']);
    Route::get('consumer', [ConsumerController::class, 'show']);
    Route::get('consumer/{id}', [ConsumerController::class, 'profile'])->whereNumber('id');
    Route::post('create/consumer/billing/{id}', [BillingController::class, 'store'])->whereNumber('id');
});

Route::middleware([AdminOnly::class, 'auth'])->group(function () {

    Route::get('user/edit/{id}', [UserController::class, 'showEdit'])->whereNumber('id');
    Route::get('admin/users', [UserController::class, 'all']);
    Route::post('user/edit/{id}', [UserController::class, 'edit'])->whereNumber('id');
    Route::post('user/delete/{id}', [UserController::class, 'delete'])->whereNumber('id');


    Route::post('billing/delete/{billing_id}', [BillingController::class, 'delete'])->whereNumber('id');
    Route::get('billing/edit/{billing_id}', [BillingController::class, 'showEdit'])->whereNumber('id');
    Route::post('billing/edit/{billing_id}', [BillingController::class, 'edit'])->whereNumber('id');

    Route::post('admin/create/consumer', [ConsumerController::class, 'store']);
    Route::get('admin/consumer/edit/{id}', [ConsumerController::class, 'showEdit'])->whereNumber('id');
    Route::post('admin/consumer/edit/{id}', [ConsumerController::class, 'edit'])->whereNumber('id');
    Route::post('admin/consumer/delete/{id}', [ConsumerController::class, 'delete'])->whereNumber('id');

    Route::get('admin/register/admin', [RegisterController::class, 'createAdmin']);
    Route::get('admin/register/collector', [RegisterController::class, 'createCollector']);
    Route::get('admin/register/cashier', [RegisterController::class, 'createCashier']);
    Route::post('admin/register/cashier', [RegisterController::class, 'storeCashier']);
    Route::post('admin/register/admin', [RegisterController::class, 'storeAdmin']);
    Route::post('admin/register/collector', [RegisterController::class, 'storeCollector']);
});
