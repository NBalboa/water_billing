<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class TransactionController extends Controller
{
    //

    public function all()
    {
        $transactions = Transaction::with('billing', 'cashier');


        if (request()->has('table_search') && request('table_search') != null) {
            $search  = request('table_search');
            // dd($search);
            $transactions = Transaction::with('billing', 'cashier')
                ->where('id', intval($search))
                ->orWhere('billing_id', intval($search));

            if (request()->has("month") && request()->month != null) {
                $month = request()->month;
                $transactions->whereHas('billing', function ($query) use ($month) {
                    $query->whereMonth('paid_at', $month);
                });
            }

            if (request()->has("year") && request()->year != null) {
                $year = request()->year;
                $transactions->whereHas('billing', function ($query) use ($year) {
                    $query->whereYear('paid_at', $year);
                });
            }

            $transactions = $transactions->get();
        } else {
            if (request()->has("month") && request()->month != null) {
                $month = request()->month;
                $transactions->whereHas('billing', function ($query) use ($month) {
                    $query->whereMonth('paid_at', $month);
                });
            }

            if (request()->has("year") && request()->year != null) {
                $year = request()->year;
                $transactions->whereHas('billing', function ($query) use ($year) {
                    $query->whereYear('paid_at', $year);
                });
            }

            $transactions = $transactions->get();
        }

        $years = Transaction::join('billings', 'transactions.id', '=', 'billings.id')
            ->selectRaw('YEAR(billings.paid_at) as year')
            ->distinct()
            ->get();

        // dd($years);

        return view('transaction', compact('transactions', 'years'));
    }




    public function print($billing_id)
    {
        $transaction = Transaction::with('billing', 'cashier')->where('billing_id', $billing_id)->get()->firstOrFail();
        return view('print.transaction', ["transaction" => $transaction]);
    }


    public function prints($year, $month)
    {
        $transactions = Transaction::with('billing', 'cashier');

        if ($year !== "blank") {
            $transactions->whereHas('billing', function ($query) use ($year) {
                $query->whereYear('paid_at', $year);
            });
        }

        if ($month !== "blank") {
            $transactions->whereHas('billing', function ($query) use ($month) {
                $query->whereMonth('paid_at', $month);
            });
        }

        $transactions = $transactions->get();

        return view('print.transactions', ['transactions' => $transactions]);
    }

    public function search($search)
    {
        if ($search !== null && $search !== 'all') {
            $transactions = Transaction::with('billing', 'cashier')
                ->where('id', intval($search))
                ->orWhere('billing_id', intval($search));
            $transactions = $transactions->get();
        } else {
            $transactions = Transaction::with('billing', 'cashier')->get();
        }

        $output = "";

        foreach ($transactions as $transaction) {
            $paid_at = Carbon::parse($transaction->billing->paid_at);

            $output .= '

            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Transaction</h3>
                    </div>
                    <div class="card-body">
                        <p><span class="font-weight-bold">Transaction
                                No: </span>' . sprintf('%07d', $transaction->id) . '</p>
                        <p><span class="font-weight-bold">Billing No:
                            </span>' . sprintf('%07d', $transaction->billing->id) . '</p>
                        <p><span class="font-weight-bold">Cashier:
                            </span>' . $transaction->cashier->first_name . '
                            ' . $transaction->cashier->last_name . '</p>
                        <p><span class="font-weight-bold">Consumer:
                            </span>' . $transaction->billing->consumer->first_name . '
                            ' . $transaction->billing->consumer->last_name . '</p>
                        <p><span class="font-weight-bold">Paid: </span>' . $paid_at->format('F j, Y g:i A') . '
                        </p>
                        <p><span class="font-weight-bold">Amount: </span>' . $transaction->billing->money . '</p>
                        <p><span class="font-weight-bold">Change: </span>' . $transaction->billing->change . '</p>

                    </div>
                </div>
            </div>
            ';
        }

        return response($output);
    }
}
