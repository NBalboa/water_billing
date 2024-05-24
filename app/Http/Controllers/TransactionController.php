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

            if (request()->has("from") && request()->from != null && request()->has('to') && request()->to != null) {
                $transaction_start =
                    Carbon::createFromFormat('Y-m-d', request()->from)->startOfDay();
                $transaction_end = Carbon::createFromFormat('Y-m-d', request()->to)->endOfDay();
                $transactions->whereHas('billing', function ($query) use ($transaction_start, $transaction_end) {
                    $query->whereBetween('paid_at', [$transaction_start, $transaction_end]);
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

            if (request()->has("from") && request()->from != null && request()->has('to') && request()->to != null) {
                $transaction_start =
                    Carbon::createFromFormat('Y-m-d', request()->from)->startOfDay();
                $transaction_end = Carbon::createFromFormat('Y-m-d', request()->to)->endOfDay();
                $transactions->whereHas('billing', function ($query) use ($transaction_start, $transaction_end) {
                    $query->whereBetween('paid_at', [$transaction_start, $transaction_end]);
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


    public function prints($year, $month, $from, $to)
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

        if ($from !== "blank" && $to !== "blank") {
            $transaction_start =
                Carbon::createFromFormat('Y-m-d', request()->from)->startOfDay();
            $transaction_end = Carbon::createFromFormat('Y-m-d', request()->to)->endOfDay();
            $transactions->whereHas('billing', function ($query) use ($transaction_start, $transaction_end) {
                $query->whereBetween('paid_at', [$transaction_start, $transaction_end]);
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
                    <tr class="text clickable-tr "
                        data-href="/transaction/print/' . $transaction->billing->id . '"
                        style="cursor: pointer;">
                        <td>' . $transaction->billing->consumer->meter_code . '</td>
                        <td>' . sprintf('%07d', $transaction->billing->id) . '</td>
                        <td>' . $transaction->billing->consumer->first_name . '
                            ' . $transaction->billing->consumer->last_name . '</td>
                        <td>' . $transaction->billing->consumer->street . ',
                            ' . $transaction->billing->consumer->barangay . '</td>
                        <td>' . $paid_at->format('F j, Y') . '
                        </td>
                        <td>' . $paid_at->format('F') . '
                        </td>
                        <td>' . $transaction->cashier->first_name . '
                            ' . $transaction->cashier->last_name . '</td>
                    </tr>
            
            ';
        }

        return response($output);
    }
}
