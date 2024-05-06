<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class TransactionController extends Controller
{
    //

    public function all()
    {

        if (request()->has('table_search') && request('table_search') != null) {
            $search  = request('table_search');
            // dd($search);
            $transactions = Transaction::with('billing', 'cashier')
                ->where('id', intval($search))
                ->orWhere('billing_id', intval($search));

            $transactions = $transactions->get();
        } else {
            $transactions = Transaction::with('billing', 'cashier')->paginate(5);
        }

        // dd($transactions);
        return view('transaction', compact('transactions'));
    }

    public function all_search()
    {
        $transactions = Transaction::with('billing', 'cashier');

        dd($transactions);
        $output = "";

        foreach ($transactions as $transaction) {
            $paid_at = Carbon::parse($transaction->billing->paid_at);
            $output .= '

            <tr>
                <td> ' . sprintf('%07d', $transaction->id) . '</td>
                <td>' . sprintf('%07d', $transaction->billing->id) . '</td>
                <td>' . $paid_at->format('F j, Y g:i A') . '</td>
                <td' . $transaction->billing->money . '</td>
                <td>' . $transaction->billing->change . '</td>
                <td>' . $transaction->cashier->first_name . '
                    ' . $transaction->cashier->last_name . '</td>
                <td>' . $transaction->billing->consumer->first_name . '
                    ' . $transaction->billing->consumer->last_name . '</td>
            </tr>
            
            ';
        }

        return response($output);
    }

    public function search($search)
    {
        // dd()
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
            // $output .= '

            // <tr>
            //     <td> ' . sprintf('%07d', $transaction->id) . '</td>
            //     <td>' . sprintf('%07d', $transaction->billing->id) . '</td>
            //     <td>' . $paid_at->format('F j, Y g:i A') . '</td>
            //     <td' . $transaction->billing->money . '</td>
            //     <td>' . $transaction->billing->change . '</td>
            //     <td>' . $transaction->cashier->first_name . '
            //         ' . $transaction->cashier->last_name . '</td>
            //     <td>' . $transaction->billing->consumer->first_name . '
            //         ' . $transaction->billing->consumer->last_name . '</td>
            // </tr>

            // ';
        }

        return response($output);
    }
}
