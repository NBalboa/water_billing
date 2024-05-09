@extends('admin')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <p>Billing Details</p>
            </div>
            <section class="content">
                @if ($billing->consumer)
                    <div class="container-fluid border border-black w-50" id="print_content">
                        <h3 class="text-center mt-4">Payment Details</h3>
                        <div class="wrapper m-4">
                            @php
                                $reading_date = Carbon\Carbon::parse($billing->reading_date);
                                $due_date = Carbon\Carbon::parse($billing->due_date);
                                $after_due_date = $reading_date->greaterThan($due_date);
                            @endphp

                            <div class="d-flex justify-content-between mx-5">
                                <span class="font-weight-bold">Bill No.</span>
                                <span class="font-weight-bolder">{{ sprintf('%07d', $billing->id) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5">
                                <span class="font-weight-bold">Date Created</span>
                                <span class="font-weight-bolder">{{ $reading_date->format('F j, Y g:i A') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5">
                                <span class="font-weight-bold">Due Date</span>
                                <span class="font-weight-bolder">{{ $due_date->format('F j, Y g:i A') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5">
                                <span class="font-weight-bold">Collector</span>
                                <span class="font-weight-bolder">{{ $billing->collector->first_name }}
                                    {{ $billing->collector->last_name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5">
                                <span class="font-weight-bold">Acount No.</span>
                                <span class="font-weight-bolder">{{ sprintf('%07d', $billing->consumer->id) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Account Name</span>
                                <span class="font-weight-bolder">{{ $billing->consumer->first_name }}
                                    {{ $billing->consumer->last_name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Meter Code</span>
                                <span class="font-weight-bolder">{{ $billing->consumer->meter_code }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Previous</span>
                                <span class="font-weight-bolder">{{ $billing->previos }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Current</span>
                                <span class="font-weight-bolder">{{ $billing->current }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Total Consumption</span>
                                <span class="font-weight-bolder">{{ $billing->total_consumption }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Water Bill</span>
                                <span class="font-weight-bolder">{{ $billing->price }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Source Charge</span>
                                <span class="font-weight-bolder">20</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Penalty</span>
                                <span
                                    class="font-weight-bolder">{{ intval($result) === 0 ? 50 : intval($result) * 50 }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Total Amount Due</span>
                                <span class="font-weight-bolder">{{ $billing->total }}</span>
                            </div>
                            <div class="d-flex justify-content-between mx-5 my">
                                <span class="font-weight-bold">Total Amount After Due</span>
                                @if (intval($result) === 0)
                                    <span class="font-weight-bolder"> {{ $billing->after_due }}
                                    </span>
                                @else
                                    <span class="font-weight-bolder">{{ number_format($payment, 2) }}
                                    </span>
                                @endif
                            </div>
                            @if (intval($result >= 1 && $billing->status === 'PENDING'))
                                <p>Note: <i>This billing is delayed by {{ intval($result) }} week/s</i></p>
                            @endif


                            @if ($billing->status === 'PENDING')
                                {{-- if() --}}

                                @if (auth()->user()->status == 2)
                                    <form method="POST" action="/billing/pay/{{ $billing->id }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="number" class="form-control" id="total" min="0"
                                                value="{{ $payment }}" hidden readonly>
                                            <label for="money">Money</label>
                                            <input type="number" class="form-control" id="money" name="money"
                                                value="{{ old('money') }}" min="{{ $payment }}">
                                            @error('money')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="change">Change</label>
                                            <input type="number" class="form-control" id="change" name="change"
                                                min="0" value="{{ old('change') }}" min="0" readonly>
                                            @error('change')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                @endif
                            @else
                                <div class="d-flex justify-content-between mx-5 my">
                                    <span class="font-weight-bold">Amount</span>
                                    <span class="font-weight-bolder">{{ $billing->money }}</span>
                                </div>
                                <div class="d-flex justify-content-between mx-5 my">
                                    <span class="font-weight-bold">Change</span>
                                    <span class="font-weight-bolder">{{ $billing->change }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <h2>Deleted Consumer</h2>
                @endif
            </section>
            @if ($billing->consumer)
                <a href="/billing/print/{{ $billing->id }}" class="btn btn-info" id="printBtn">Preview Print</a>
                <a href="/consumer/{{ $billing->consumer->id }}" class="btn btn-default" id="printBtn">Back</a>
            @endif

        </section>
    </div>
@endsection
