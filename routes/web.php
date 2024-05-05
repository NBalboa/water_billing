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
use App\Models\Transaction;
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
        if (auth()->user()->status === 0) {

            $transactions_daily =
                Billing::where('status', "PAID")
                ->whereDate('paid_at', $currentDate)->get();

            $transactions_monthly =
                Billing::where('status', "PAID")
                ->whereMonth('paid_at', $currentMonth)->get();


            $daily_sales = 0.00;
            $monthly_sales = 0.00;

            foreach ($transactions_daily as $transaction_daily) {
                $reading_date = Carbon::parse($transaction_daily->reading_date);
                $paid_at = Carbon::parse($transaction_daily->paid_at);
                $result = $reading_date->diffInWeeks($paid_at);

                $payment = $transaction_daily->price;
                if ($result >= 1) {
                    if ($result > 8) {
                        $result = 8;
                    }
                    $payment = $transaction_daily->price + (intval($result) * 50);
                }
                $daily_sales += $payment;
            }

            foreach ($transactions_monthly as $transaction_monthly) {
                $reading_date = Carbon::parse($transaction_monthly->reading_date);
                $paid_at = Carbon::parse($transaction_monthly->paid_at);
                $result = $reading_date->diffInWeeks($paid_at);
                $payment = $transaction_monthly->price;
                if ($result >= 1) {
                    if ($result > 8) {
                        $result = 8;
                    }
                    $payment = $transaction_monthly->price + (intval($result) * 50);
                }
                $monthly_sales += $payment;
            }
        } else {
            $transactions_daily =
                Transaction::with('cashier', 'billing')
                ->where('cashier_id', auth()->user()->id)
                ->whereHas('billing', function ($query) use ($currentDate) {
                    $query->whereDate('paid_at', $currentDate);
                })->get();
            $transactions_monthly =
                Transaction::with('cashier', 'billing')
                ->where('cashier_id', auth()->user()->id)
                ->whereHas('billing', function ($query) use ($currentMonth) {
                    $query->whereMonth('paid_at', $currentMonth);
                })->get();

            $daily_sales = 0.00;
            $monthly_sales = 0.00;

            foreach ($transactions_daily as $transaction_daily) {
                $reading_date = Carbon::parse($transaction_daily->billing->reading_date);
                $paid_at = Carbon::parse($transaction_daily->billing->paid_at);
                $result = $reading_date->diffInWeeks($paid_at);

                $payment = $transaction_daily->billing->price;
                if ($result >= 1) {
                    if ($result > 8) {
                        $result = 8;
                    }
                    $payment = $transaction_daily->billing->price + (intval($result) * 50);
                }
                $daily_sales += $payment;
            }

            foreach ($transactions_monthly as $transaction_monthly) {
                $reading_date = Carbon::parse($transaction_monthly->billing->reading_date);
                $paid_at = Carbon::parse($transaction_monthly->billing->paid_at);
                $result = $reading_date->diffInWeeks($paid_at);
                $payment = $transaction_monthly->billing->price;
                if ($result >= 1) {
                    if ($result > 8) {
                        $result = 8;
                    }
                    $payment = $transaction_monthly->billing->price + (intval($result) * 50);
                }
                $monthly_sales += $payment;
            }
            // dd($daily_sales);
        }
        // ->;

        return view('home', [
            'total_consumer' => $total_consumer,
            'total_paid' => $total_paid,
            'total_pending' => $total_pending,
            'daily_sales' => number_format($daily_sales, 2),
            'monthly_sales' => number_format($monthly_sales, 2)
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
    Route::get('consumer/search/{search}', [ConsumerController::class, 'search']);
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
