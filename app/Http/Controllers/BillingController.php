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

    public function printAll($month, $year, $status, $search, $from, $to)
    {
        $attributes = [
            'month' => $month == 'blank' ? null : $month,
            'year' => $year == 'blank' ? null : $year,
            'status' => $status == 'blank' ? null : $status,
            'search' => $search == 'blank' ? null : $search,
            'from' => $from == 'blank' ? null : $from,
            'to' => $to == 'blank' ? null : $to,
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

                            if ($attributes['from'] && $attributes['from'] != null && $attributes['to'] && $attributes != null) {
                                $billing_start = Carbon::createFromFormat('Y-m-d', $attributes['from'])->startOfDay();
                                $billing_end = Carbon::createFromFormat('Y-m-d', $attributes['to'])->endOfDay();
                                $query->whereBetween('created_at', [$billing_start, $billing_end]);
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
                if ($attributes['from'] && $attributes['from'] != null && $attributes['to'] && $attributes != null) {
                    $billing_start = Carbon::createFromFormat('Y-m-d', $attributes['from'])->startOfDay();
                    $billing_end = Carbon::createFromFormat('Y-m-d', $attributes['to'])->endOfDay();
                    $billings->whereBetween('created_at', [$billing_start, $billing_end]);
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
            if ($result > 8) {
                $result = 8;
            }
            $payment = $billing->price + (intval($result) * 50);
        }


        return view('profile.billing', ['billing' => $billing, 'payment' => $payment, 'result' => $result]);
    }

    public function all()
    {
        $billings = Billing::with('consumer', 'collector');

        if (request()->has('search')) {
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

                            if (request()->has('from') && request()->from != null && request()->has('to') && request()->to != null) {
                                $billing_start = Carbon::createFromFormat('Y-m-d', request()->from)->startOfDay();
                                $billing_end = Carbon::createFromFormat('Y-m-d', request()->to)->endOfDay();
                                $query->whereBetween('created_at', [$billing_start, $billing_end]);
                            }
                            $query->whereAny(['meter_code', 'first_name', 'last_name'], 'LIKE', '%' . $search . '%');
                        });
                    });
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

                if (request()->has('from') && request()->from != null && request()->has('to') && request()->to != null) {
                    $billing_start = Carbon::createFromFormat('Y-m-d', request()->from)->startOfDay();
                    $billing_end = Carbon::createFromFormat('Y-m-d', request()->to)->endOfDay();
                    $billings->whereBetween('created_at', [$billing_start, $billing_end]);
                }
            }
            $billings = $billings->get();
        } else {
            $billings = Billing::with('consumer', 'collector')->latest()->paginate(5);
        }


        $pendings = Billing::with("consumer", "collector")
            ->where("status", "PENDING")->get();
        $disconnections = [];
        foreach ($pendings as $pending) {
            $reading_date = Carbon::parse($pending->reading_date);
            $current_date = Carbon::now()->setTimezone("Asia/Manila");

            if ($reading_date->diffInWeeks($current_date) >= 8) {
                array_push($disconnections, $pending);
            }
        }


        $years = Billing::getYears();

        return view('billing', ['billings' => $billings, 'years' => $years, 'disconnections' => $disconnections]);
    }

    public function print_disconnection()
    {
        $pendings = Billing::with("consumer", "collector")
            ->where("status", "PENDING")->get();
        $disconnections = [];
        foreach ($pendings as $pending) {
            $reading_date = Carbon::parse($pending->reading_date);
            $current_date = Carbon::now()->setTimezone("Asia/Manila");

            if ($reading_date->diffInWeeks($current_date) >= 8) {
                array_push($disconnections, $pending);
            }
        }
        if (count($disconnections) == 0) {
            return redirect("/all/billings");
        }

        return view('print.disconnections', ['billings' => $disconnections]);
    }

    public function billing_search($search)
    {
        $billings = Billing::with('consumer', 'collector');

        if ($search !== null && $search !== 'all') {

            $billings->orWhere('id', intval($search))
                ->orWhereHas(
                    'consumer',
                    function ($query) use ($search) {
                        $query->whereAny(['meter_code', 'first_name', 'last_name'], 'LIKE', '%' . $search . '%');
                    }
                );
        }


        $billings = $billings->get();
        $output = "";

        foreach ($billings as $billing) {

            $reading_date = Carbon::parse($billing->reading_date);
            $current_date = Carbon::now()->setTimezone('Asia/Manila');
            if ($billing->status == 'PAID') {
                $current_date = Carbon::parse($billing->paid_at);
            }
            $result = $reading_date->diffInWeeks($current_date);
            $payment = $billing->price;
            if ($result >= 1) {
                if ($result > 8) {
                    $result = 8;
                }
                $payment = $billing->price + intval($result) * 50;
            }

            $latest_date_paid = Billing::where(
                'consumer_id',
                $billing->consumer->id,
            )
                ->whereNotNull('paid_at')
                ->where('status', 'PAID')
                ->latest()
                ->first();
            $output .= '
                <tr class=
                "text clickable-tr ' . (intval($result) >= 8 && $billing->status === 'PENDING' ? 'text-danger' : 'text-dark') . '"
                    data-href="' . ($billing->status == 'PENDING' ? "/billing/print/$billing->id" : "/transaction/print/$billing->id") . '"
                    style="cursor: pointer;">
                    <td>' . $billing->consumer->meter_code . '</td>
                    <td>' . sprintf('%07d', $billing->id) . '</td>
                    <td>' . $billing->consumer->first_name . '
                        ' . $billing->consumer->last_name . '</td>
                    <td>' . $billing->consumer->street . ',
                        ' . $billing->consumer->barangay . '</td>
                    <td>' . (!$latest_date_paid ? '' : Carbon::parse($latest_date_paid->paid_at)->format('M')) . '
                    </td>
                    <td>' . (intval($result) === 0 ? $billing->total : number_format($payment, 2)) . '
                    </td>
                </tr>
            ';
        }

        return response($output);
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

    public function invoices_search($search)
    {

        if ($search !== null && $search !== 'all') {

            $billings = Billing::with('consumer')->where('status', 'PENDING')
                ->where('id', intval($search));
        } else {
            $billings = Billing::with('consumer')
                ->where('status', 'PENDING');
        }

        $billings = $billings->get();

        $output = "";

        foreach ($billings as $billing) {
            $reading_date = Carbon::parse($billing->reading_date);

            $output .=
                '
                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Invoice</h3>
                        </div>
                        <div class="card-body">

                            <p><span class="font-weight-bold">Billing No :
                                </span>' . sprintf('%07d', $billing->id) . '</p>
                            <p><span class="font-weight-bold">Meter Code :
                                </span>' . $billing->consumer->meter_code . '</p>
                            <p><span class="font-weight-bold">Consumer Name :
                                </span>' . $billing->consumer->first_name . '
                                ' . $billing->consumer->last_name . '</p>
                            <p><span class="font-weight-bold">Status : </span>' . $billing->status . '</p>
                            <p><span class="font-weight-bold">Total Consumption :
                                </span>' . $billing->total_consumption . '</p>
                            <p><span class="font-weight-bold">Total : </span>' . $billing->total . '</p>
                            <p><span class="font-weight-bold">Total Amount After Due :
                                </span>' . $billing->after_due . '</p>

                        </div>
                        <div class="card-footer">
                            <a href="/billing/' . $billing->id . '" class="btn btn-info text-right">
                                Pay
                            </a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                ';
        }
        return response($output);
    }

    public function print($id)
    {
        $billing = Billing::with('consumer', 'collector')->findOrFail($id);
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
                if ($result > 8) {
                    $result = 8;
                }
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

        return redirect("/transaction/print/{$id}");
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
