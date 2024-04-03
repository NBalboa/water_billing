@extends('admin')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <p>Consumer Profile</p>

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
                            {{ $consumer->address }}
                        </p>
                        <hr>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Billings</h3>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Reading Date</th>
                                    <th>Due Date</th>
                                    <th>Previous</th>
                                    <th>Current</th>
                                    <th>Total Consumtion</th>
                                    <th>Status</th>
                                    <th>Water Bill</th>
                                    <th>After Due</th>
                                    <th>Grand Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{ dd($consumer->billings) }} --}}
                                @if ($consumer->billings->isEmpty())
                                    <tr>
                                        <td>No Billings Yet</td>
                                    </tr>
                                @else
                                    @foreach ($consumer->billings as $billing)
                                        <tr>
                                            @php
                                                $reading_date = Carbon\Carbon::parse($billing->reading_date);
                                                $due_date = Carbon\Carbon::parse($billing->due_date);
                                            @endphp
                                            <td>{{ $reading_date->format('F j, Y g:i A') }}</td>
                                            <td>{{ $due_date->format('F j, Y g:i A') }}</td>
                                            <td>{{ $billing->previos }}</td>
                                            <td>{{ $billing->current }}</td>
                                            <td>{{ $billing->total_consumption }}</td>
                                            <td>{{ $billing->status }}</td>
                                            <td><strong>₱ </strong>{{ $billing->price }}</td>
                                            <td><strong>₱ </strong>{{ $billing->after_due }}</td>
                                            <td><strong>₱ </strong>{{ $billing->total }}</td>
                                            <td>
                                                @auth
                                                    @if (auth()->user()->status == 0)
                                                        <form action="/billing/delete/{{ $billing->id }}" method="POST"
                                                            style="display: inline-block">
                                                            @csrf
                                                            <button class="btn btn-danger" type="submit">Delete</button>
                                                        </form>
                                                        <a href="/billing/{{ $billing->id }}" class="btn btn-info text-right">
                                                            Details
                                                        </a>
                                                        <a href="/billing/edit/{{ $billing->id }}"
                                                            class="btn btn-default">Edit</a>
                                                    @endif
                                                    <a class="btn btn-dark" href="/billing/print/{{ $billing->id }}">Print</a>
                                                @endauth

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- /.card-body -->
                </div>
                <div class="row">
                    <div class="col-sm-6 offset-sm-6 text-right">
                        <button type="button" class="btn btn-info text-right" data-toggle="modal"
                            data-target="#create_billing">
                            Create
                        </button>
                    </div>
                </div>
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
                                <form method="POST" action="/create/consumer/billing/{{ $consumer->id }}">
                                    @csrf
                                    <div class="card-body">

                                        <div class="form-group">
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
                                                name="total_consumption" value="{{ old('total_consumption') }}" readonly>
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
                                            <label for="total">Grand Total</label>
                                            <input type="number" class="form-control" id="total" name="total"
                                                placeholder="Grand Total" value="{{ old('total') }}" readonly>
                                            @error('total')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
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
