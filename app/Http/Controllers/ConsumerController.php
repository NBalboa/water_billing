<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Consumer;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    //
    public function show()
    {

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

        return view('update.consumer', ['consumer' => $consumer]);
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
            'provinces' => ['required'],
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
            'provinces' => ['required'],
        ]);

        $attributes['meter_code'] = $meterCode;
        $attributes['address'] = strtolower("{$attributes['street']}, {$attributes['provinces']}");


        Consumer::create($attributes);

        return redirect('/consumer')->with('success', 'Consumer Created');
    }
}
