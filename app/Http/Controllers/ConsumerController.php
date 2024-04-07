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

            $consumers = Consumer::where('barangay', $area->name)->get();
            return view('consumer', ['consumers' => $consumers,]);
        }

        return view('consumer', ['consumers' => $this->searchConsumer()]);
    }

    protected function searchConsumer()
    {
        return Consumer::latest()->filter()->get();
    }


    public function profile($id)
    {
        $consumer = Consumer::with('billings')->findOrFail($id);

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

        $consumer->delete();

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
