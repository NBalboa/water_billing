<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\BillingArea;
use App\Models\Consumer;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    //
    public function show()
    {

        if (auth()->user()->status == 1) {
            $area = BillingArea::find(auth()->user()->assign_id);


            if (request('table_search') ?? false) {
                $area = BillingArea::find(auth()->user()->assign_id);
                $consumers = Consumer::where('is_deleted', 0)
                    ->where('barangay', $area->name)
                    ->whereAny(
                        ['meter_code', 'first_name', 'last_name', 'phone_no', 'street', 'barangay'],
                        'LIKE',
                        '%' . request('table_search') . '%'
                    )->get();
            } else {
                $consumers = Consumer::where('is_deleted', 0)
                    ->where('barangay', $area->name)->paginate(10);
            }

            return view('consumer', ['consumers' => $consumers,]);
        }
        return view('consumer', ['consumers' => $this->searchConsumer()]);
    }

    protected function searchConsumer()
    {
        return Consumer::where('is_deleted', 0)->latest()->filter()->paginate(10);
    }

    public function search($search)
    {
        $output = "";

        if (auth()->user()->status == 1) {
            $area = BillingArea::find(auth()->user()->assign_id);
            if ($search) {
                $area = BillingArea::find(auth()->user()->assign_id);
                $consumers = Consumer::where('is_deleted', 0)
                    ->where('barangay', $area->name)
                    ->whereAny(
                        ['meter_code', 'first_name', 'last_name', 'phone_no', 'street', 'barangay'],
                        'LIKE',
                        '%' . $search . '%'
                    )->get();
            } else {
                $consumers = Consumer::where('is_deleted', 0)
                    ->where('barangay', $area->name)->paginate(10);
            }
        } else {
            if ($search) {
                $consumers = Consumer::where('is_deleted', 0)->whereAny(
                    ['meter_code', 'first_name', 'last_name', 'phone_no', 'street', 'barangay'],
                    'LIKE',
                    '%' . $search . '%'
                )->get();
            } else {
                $consumers = Consumer::where('is_deleted', 0);
            }
        }


        foreach ($consumers as $consumer) {
            $output .= '
             <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Consumer</h3>
                    </div>
                    <div class="card-body">
                        <a href="/consumer/' . $consumer->id . '">
                            <p class="text-dark"><span class="font-weight-bold">Meter Code : </span>
                                ' . $consumer->meter_code . '
                            </p>
                        </a>
                        <a href="/consumer/' . $consumer->id . '">
                            <p class="text-dark">
                                <span class="font-weight-bold text-dark">Consumer Name : </span>
                                ' . $consumer->first_name . ' ' . $consumer->last_name . '
                            </p>
                        </a>
                        <a href="/consumer/' . $consumer->id . '">

                            <p class="text-dark"><span class="font-weight-bold">Phono No :
                                </span>' . $consumer->phone_no . '
                            </p>
                        </a>
                        <a href="/consumer/' . $consumer->id . '">
                            <p class="text-dark"><span class="font-weight-bold">Address :
                                </span>' . $consumer->street . ',
                                ' . $consumer->barangay . '</p>
                        </a>

                    </div>
                </div>
            </div>
            ';
            // <tr>
            //     <td>' . $consumer->meter_code . '</td>
            //     <td>
            //         <a href="/consumer/' . $consumer->id . ' " class="text-dark">
            //             ' . $consumer->first_name . ' ' . $consumer->last_name . '
            //         </a>
            //     </td>
            //     <td>' . $consumer->phone_no . '</td>
            //     <td>' . $consumer->street . ', ' . $consumer->barangay . '</td>
            // </tr>
        }

        return response($output);
    }

    public function profile($id)
    {
        $consumer = Consumer::with('billings')->findOrFail($id);

        if ($consumer->is_deleted == 1) {
            return redirect('/consumer');
        }

        return view('profile.consumer', ['consumer' => $consumer]);
    }

    public function showEdit($id)
    {
        $consumer = Consumer::findOrFail($id);
        $areas = BillingArea::all();
        return view('update.consumer', ['consumer' => $consumer, 'areas' => $areas]);
    }

    public function delete($id)

    {
        $consumer = Consumer::findOrFail($id);

        $consumer->is_deleted = 1;

        $consumer->save();

        return redirect("/consumer");
    }

    public function edit($id)
    {
        $consumer = Consumer::findOrFail($id);

        $attributes = request()->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone_no' => ['required'],
            'street' => ['required'],
            'barangay' => ['required'],
        ]);


        $consumer->update($attributes);

        return redirect("/consumer");
    }

    public function store()
    {
        $latestConsumer = Consumer::latest()->first();
        $incrementId = $latestConsumer ? ++$latestConsumer->id : 1;
        $formattedId = sprintf('%07d', $incrementId);
        $meterCode = date('Y') . '-' . $formattedId;


        $attributes = request()->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone_no' => ['required'],
            'street' => ['required'],
            'barangay' => ['required'],
        ]);

        $attributes['meter_code'] = $meterCode;


        Consumer::create($attributes);

        return redirect('/consumer')->with('success', 'Consumer Created');
    }
}
