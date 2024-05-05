<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Consumer;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillingController extends Controller
{

    public function showEdit($id)
    {
        $billing = Billing::with('consumer')->findOrFail($id);

        // dd($billing);

        return view('update.billing', ['billing' => $billing]);
    }

    public function edit($id)
    {
        $billing = Billing::with('consumer')->findOrFail($id);


        if ($billing->status == "PAID") {
            $attributes = request()->validate([
                'previos' => ['required'],
                'current' => ['required'],
                'total_consumption' => ['required'],
                'price' => ['required'],
                'total' => ['required'],
                'source_charges' => ['required'],
                "money" => ['required'],
                "change" => ['required']
            ]);
        } else {
            $attributes = request()->validate([
                'previos' => ['required'],
                'current' => ['required'],
                'total_consumption' => ['required'],
                'price' => ['required'],
                'total' => ['required'],
                'source_charges' => ['required'],
            ]);
        }

        $billing->update($attributes);

        return redirect("/all/billings")->with('success', 'Successfully Updated');
    }

    public function delete($id)
    {
        $billing = Billing::findOrFail($id);

        $billing->delete();

        return back();
    }

    public function printAll($month, $year, $status, $search)
    {
        $attributes = [
            'month' => $month == 'blank' ? null : $month,
            'year' => $year == 'blank' ? null : $year,
            'status' => $status == 'blank' ? null : $status,
            'search' => $search == 'blank' ? null : $search,
        ];
        if ($attributes) {
            $billings = Billing::with('consumer');
            if ($attributes['search'] && request()->search != null) {
                $search = $attributes['search'];
                $billings->orWhere('id', $search)
                    ->orWhereHas('consumer', function ($query) use ($search, $attributes) {
                        $query->where(function ($query) use ($search, $attributes) {
                            if ($attributes['year'] && $attributes['year'] != null) {
                                $query->whereYear('reading_date', $attributes['year']);
                            }
                            if ($attributes['month'] && $attributes['month'] != null) {
                                $query->whereMonth('reading_date', $attributes['month']);
                            }

                            if ($attributes['status']) {
                                if ($attributes['status'] != 'on') {
                                    $query->where('status', $attributes['status'] == 'pending' ? 'PENDING' : 'PAID');
                                }
                            }
                            $query->whereAny(['meter_code', 'first_name', 'last_name'], 'LIKE', '%' . $search . '%');
                        });
                    });
            } else {
                if ($attributes['year'] && $attributes['year'] != null) {
                    $billings->whereYear('reading_date', $attributes['year']);
                }

                if ($attributes['month'] && $attributes['month'] != null) {
                    $billings->whereMonth('reading_date', $attributes['month']);
                }

                if ($attributes['status']) {
                    if ($attributes['status'] != 'on') {
                        $billings->where('status', $attributes['status'] == 'pending' ? 'PENDING' : 'PAID');
                    }
                }
            }
            $billings = $billings->get();
        } else {
            $billings = Billing::with('consumer')->latest()->get();
        }



        return view('receipts', ['billings' => $billings]);
    }

    public function pending()
    {
        $billings = Billing::with('consumer')->where('status', "PENDING")->latest()->get();
        $years = Billing::getYears();
        return view('billings', ['billings' => $billings, 'years' => $years]);
    }
    public function paid()
    {
        $billings = Billing::with('consumer')->where('status', "PAID")->latest()->get();
        $years = Billing::getYears();
        return view('billings', ['billings' => $billings, 'years' => $years]);
    }

    public function billing($id)
    {
        $billing = Billing::with('consumer', 'collector')->findOrFail($id);

        $reading_date = Carbon::parse($billing->reading_date);
        $current_date = Carbon::now()->setTimezone('Asia/Manila');

        $result = $reading_date->diffInWeeks($current_date);
        $payment = $billing->price;
        if ($result >= 1) {
            $payment = $billing->price + (intval($result) * 50);
        }



        return view('profile.billing', ['billing' => $billing, 'payment' => $payment, 'result' => $result]);
    }

    public function all()
    {

        $attributes = request()->all();
        if (request()->has('search')) {
            $billings = Billing::with('consumer', 'collector');

            if (request()->has('search') && request()->search != null) {
                $search = request()->search;
                $billings->orWhere('id', intval($search))
                    ->orWhereHas('consumer', function ($query) use ($search) {
                        $query->where(function ($query) use ($search) {
                            if (request()->has('year') && request()->year != null) {
                                $query->whereYear('reading_date', request()->year);
                            }

                            if (request()->has('month') && request()->month != null) {
                                $query->whereMonth('reading_date', request()->month);
                            }

                            if (request()->has('status')) {
                                if (request()->status != 'on') {
                                    $query->where('status', request()->status == 'pending' ? 'PENDING' : 'PAID');
                                }
                            }
                            $query->whereAny(['meter_code', 'first_name', 'last_name'], 'LIKE', '%' . $search . '%');
                        });
                    });
            } else if (request()->status == 'on' && request()->search == null) {
                return redirect('/all/billings');
            } else {
                if (request()->has('year') && request()->year != null) {
                    $billings->whereYear('reading_date', request()->year);
                }

                if (request()->has('month') && request()->month != null) {
                    $billings->whereMonth('reading_date', request()->month);
                }

                if (request()->has('status')) {
                    if (request()->status != 'on') {
                        $billings->where('status', request()->status == 'pending' ? 'PENDING' : 'PAID');
                    }
                }
            }
            $billings = $billings->get();
        } else {
            $billings = Billing::with('consumer', 'collector')->latest()->paginate(5);
        }
        $years = Billing::getYears();

        return view('billing', ['billings' => $billings, 'years' => $years]);
    }

    public function invoices()
    {
        $search = request('table_search');

        if ($search ?? false) {
            $billings = Billing::with('consumer')->where('status', 'PENDING')
                ->where('id', intval($search));

            $billings = $billings->get();
        } else {
            $billings = Billing::with('consumer')
                ->where('status', 'PENDING')->paginate(10);
        }

        return view('invoice', ['billings' => $billings]);
    }

    public function print($id)
    {
        $billing = Billing::with('consumer', 'collector')->findOrFail($id);
        // dd($billing);
        // dd(User::findOrFail(2));
        // $collector = User::findOrFail($billing->collector_id);

        // dd($collector);
        return view('receipt', ['billing' => $billing]);
    }

    public function pay($id)
    {
        $billing = Billing::findOrFail($id);
        if (auth()->user()->status == 2 && $billing->status == 'PENDING') {
            $attributes = request()->validate([
                "money" => ['required'],
                "change" => ['required']
            ]);

            $reading_date = Carbon::parse($billing->reading_date);
            $current_date = Carbon::now()->setTimezone('Asia/Manila');

            $result = $reading_date->diffInWeeks($current_date);
            if ($result >= 1) {
                $payment = $billing->price + (intval($result) * 50);

                if ($payment > $attributes['money']) {
                    return redirect("/billing/{$id}")->withErrors(['money' => "Not enough money. Total payment is {$payment}"]);
                }
            }

            $attributes['status'] = "PAID";
            $attributes['paid_at'] = Carbon::now()->setTimezone('Asia/Manila');
            $billing->update($attributes);
            Transaction::create(['billing_id' => $billing->id, 'cashier_id' => auth()->user()->id]);
        }

        return redirect("/billing/print/{$id}");
    }



    public function store($id)
    {

        $currentDateTime = Carbon::now();
        $dueDateTime = $currentDateTime->addDays(7);
        $penalty = 50;

        $attributes = request()->validate([
            'previos' => ['required'],
            'current' => ['required'],
            'total_consumption' => ['required'],
            'price' => ['required'],
            'total' => ['required'],
        ]);



        $currentDateTime = Carbon::now()->setTimezone('Asia/Manila');
        $dueDateTime = Carbon::now()->addDays(7)->setTimezone('Asia/Manila');
        $attributes['after_due'] = $attributes['total'] + $penalty;
        $attributes['reading_date'] = $currentDateTime;
        $attributes['due_date'] = $dueDateTime;
        $attributes['consumer_id'] = $id;
        $attributes['collector_id'] = auth()->user()->id;
        $attributes['status'] = "PENDING";


        Billing::create($attributes);

        return redirect("/consumer/{$id}")->with('success', 'Consumer Billing created Successfully');
    }
}
