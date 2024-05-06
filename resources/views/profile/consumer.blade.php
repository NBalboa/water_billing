@extends('admin')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <p>Consumer Profile</p>

                <div class="pb-3 text-right">
                    @auth
                        @if (auth()->user()->status == 0)
                            <form action="/admin/consumer/delete/{{ $consumer->id }}" method="POST" style="display: inline-block">
                                @csrf
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                            <a href="/admin/consumer/edit/{{ $consumer->id }}" class="btn btn-default">Edit</a>
                        @endif
                    @endauth
                </div>

            </div>
            <section class="content">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Details</h3>

                    </div>
                    <div class="card-body">
                        <strong>Full Name</strong>
                        <p class="text-muted">
                            {{ $consumer->first_name }} {{ $consumer->last_name }}
                        </p>
                        <hr>
                        <strong>Meter Code</strong>
                        <p class="text-muted">
                            {{ $consumer->meter_code }}
                        </p>
                        <hr>
                        <strong>Phone No.</strong>
                        <p class="text-muted">{{ $consumer->phone_no }}</p>
                        <hr>
                        <strong>Address</strong>
                        <p class="text-muted">
                            {{ $consumer->street }}, {{ $consumer->barangay }}
                        </p>
                        <hr>
                    </div>
                    <!-- /.card-body -->
                </div>
                @if (auth()->user()->status == 1)
                    <div class="row mb-2">
                        <div class="col-sm-6 offset-sm-6 text-right">
                            <button type="button" class="btn btn-info text-right" data-toggle="modal"
                                data-target="#create_billing">
                                Create
                            </button>
                        </div>
                    </div>
                @endif
                <div class="row">
                    @if ($consumer->billings->isEmpty())
                        <p>No Billings Yet</p>
                    @else
                        @foreach ($consumer->billings as $billing)
                            @php
                                $reading_date = Carbon\Carbon::parse($billing->reading_date);
                                $due_date = Carbon\Carbon::parse($billing->due_date);
                            @endphp
                            <div class="col-md-3">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Billing</h3>
                                    </div>
                                    <div class="card-body">
                                        <p><span class="font-weight-bold">Created At:
                                            </span>{{ $reading_date->format('F j, Y g:i A') }}</span>
                                        <p><span class="font-weight-bold">Due Date:
                                            </span>{{ $due_date->format('F j, Y g:i A') }}</p>
                                        <p><span class="font-weight-bold">Previos: </span>{{ $billing->previos }}</p>
                                        <p><span class="font-weight-bold">Current: </span>{{ $billing->current }}</p>
                                        <p><span class="font-weight-bold">Total Consumption:
                                            </span>{{ $billing->total_consumption }}</p>
                                        <p><span class="font-weight-bold">Status: </span>{{ $billing->status }}</p>
                                        {{-- <p><strong>₱ </strong>{{ $billing->price }}</p>
                                        <p><strong>₱ </strong>{{ $billing->after_due }}</p>
                                        <p><strong>₱ </strong>{{ $billing->total }}</p> --}}


                                    </div>
                                    @if (auth()->user()->status == 1)
                                        <div class="card-footer">
                                            <p>
                                                <a class="btn btn-dark" href="/billing/print/{{ $billing->id }}">Print</a>
                                            </p>
                                        </div>
                                    @endif

                                    <!-- /.card-body -->
                                </div>
                            </div>
                        @endforeach
                    @endif



                    <div class="modal fade" id="create_billing">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Create Billing</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @php
                                        $current_date = Carbon\Carbon::now();
                                    @endphp
                                    <form method="POST" action="/create/consumer/billing/{{ $consumer->id }}">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="previos">Date Created</label>
                                                <input type="date" class="form-control"
                                                    value="{{ $current_date->toDateString() }}" readonly>
                                                <label for="previos">Due Date</label>
                                                <input type="date" class="form-control"
                                                    value="{{ $current_date->addWeek()->toDateString() }}" readonly>
                                                <label for="previos">Cut Off Date</label>
                                                <input type="date" class="form-control"
                                                    value="{{ $current_date->addWeeks(8)->toDateString() }}" readonly>
                                                <label for="previos">Previous</label>
                                                <input type="text" class="form-control" id="previos" name="previos"
                                                    value="{{ isset($consumer->billings[0]) ? $consumer->billings[0]->current : 0 }}"
                                                    readonly>
                                                @error('previos')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="current">Current</label>
                                                <input type="number" class="form-control" id="current" name="current"
                                                    placeholder="Current"
                                                    min="{{ isset($consumer->billings[0]) ? $consumer->billings[0]->current + 1 : 0 }}"
                                                    value="{{ old('current') }}">
                                                @error('current')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="total_consumption">Total Consumption</label>
                                                <input type="number" class="form-control" id="total_consumption"
                                                    name="total_consumption" value="{{ old('total_consumption') }}"
                                                    readonly>
                                                @error('total_consumption')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="price">Water Bill</label>
                                                <input type="number" class="form-control" id="price" name="price"
                                                    value="{{ old('price') }}" readonly>
                                                @error('price')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="total" name="total"
                                                    placeholder="Grand Total" value="{{ old('total') }}" hidden>

                                            </div>

                                            <!-- /.card-body -->
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                    </form>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
            </section>
    </div><!-- /.container-fluid -->

    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
@endsection
<!-- Site wrapper -->
