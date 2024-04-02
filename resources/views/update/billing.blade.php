@extends('admin')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">

            <div class="container-fluid">
                <h3>Update Billing</h3>
            </div>
            <section class="content">
                @php
                    $reading_date = Carbon\Carbon::parse($billing->reading_date);
                    $due_date = Carbon\Carbon::parse($billing->due_date);
                    $after_due_date = $reading_date->greaterThan($due_date);
                @endphp
                <form method="POST" action="/billing/edit/{{ $billing->id }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="previos">Previous</label>
                            <input type="text" class="form-control" id="previos" name="previos"
                                value="{{ $billing->previos }}" readonly>
                            @error('previos')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="current">Current</label>
                            <input type="number" class="form-control" id="current" name="current" placeholder="Current"
                                value="{{ $billing->current }}">
                            @error('current')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="total_consumption">Total Consumption</label>
                            <input type="number" class="form-control" id="total_consumption" name="total_consumption"
                                value="{{ $billing->total_consumption }}" readonly>
                            @error('total_consumption')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">Water Bill</label>
                            <input type="number" class="form-control" id="price" name="price"
                                value="{{ $billing->price }}" readonly>
                            @error('price')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="source_charges">Others</label>
                            <input type="number" class="form-control" min="0" id="source_charges"
                                name="source_charges" placeholder="Source Charges" value="{{ $billing->source_charges }}">
                            @error('source_charges')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="total">Grand Total</label>
                            <input type="number" class="form-control" id="total" name="total"
                                placeholder="Grand Total" value="{{ $billing->total }}" readonly>
                            @error('total')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @if ($billing->status === 'PAID')
                            <input type="number" class="form-control" id="total" min="0"
                                value="{{ $billing->total }}" hidden readonly>
                            <label for="money">Money</label>
                            <input type="number" class="form-control" id="money" name="money"
                                value="{{ old('money') }}"
                                min="{{ $after_due_date ? round($billing->after_due) : round($billing->total) }}">
                            @error('money')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <div class="form-group">
                                <label for="change">Change</label>
                                <input type="number" class="form-control" id="change" name="change" min="0"
                                    value="{{ old('change') }}" min="0" readonly>
                                @error('change')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                        <!-- /.card-body -->
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </form>
            </section>
        </section>
    </div>
@endsection
