<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

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
}
