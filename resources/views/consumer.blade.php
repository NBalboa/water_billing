@extends('admin')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Consumer</h3>
                        <div class="card-tools">
                            <form method="GET" action="#">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                        placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                        </div>
                        </form>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Meter Code</th>
                                    <th>Name</th>
                                    <th>Phone No.</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($consumers->isEmpty())
                                    <tr>
                                        <td>No Consumer Yet</td>
                                    </tr>
                                @else
                                    {{-- <p>Not Empty</p> --}}
                                    @foreach ($consumers as $consumer)
                                        <tr>
                                            <td>{{ $consumer->meter_code }}</td>
                                            <td>{{ $consumer->first_name }} {{ $consumer->last_name }}</td>
                                            <td>{{ $consumer->phone_no }}</td>
                                            <td>{{ $consumer->address }}</td>
                                            <td>
                                                <a href="/consumer/{{ $consumer->id }}" class="btn btn-info">View</a>

                                                @auth
                                                    @if (auth()->user()->status == 0)
                                                        <form action="/admin/consumer/delete/{{ $consumer->id }}" method="POST"
                                                            style="display: inline-block">
                                                            @csrf
                                                            <button class="btn btn-danger" type="submit">Delete</button>
                                                        </form>
                                                        <a href="/admin/consumer/edit/{{ $consumer->id }}"
                                                            class="btn btn-default">Edit</a>
                                                    @endauth
                                                @endif


                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- /.card-body -->
                </div>
                @auth
                    @if (auth()->user()->status == 0)
                        <div class="row">
                            <div class="col-sm-6 offset-sm-6 text-right">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#create_consumer">
                                    Create
                                </button>
                            </div>
                        </div>
                        <div class="modal fade" id="create_consumer">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Create Consumer</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/admin/create/consumer">
                                            @csrf
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                                        placeholder="First Name" value="{{ old('first_name') }}">
                                                    @error('first_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                                        placeholder="Last Name" value="{{ old('last_name') }}">
                                                    @error('last_name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone_no">Phone No.</label>
                                                    <input type="number" class="form-control" id="phone_no" name="phone_no"
                                                        placeholder="Phone No." value="{{ old('phone_no') }}">
                                                    @error('phone_no')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control mb-4"
                                                            value="{{ old('street') }}" id="phone_no" name="street"
                                                            placeholder="House No./Street/Purok">
                                                        @error('street')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        <select class="custom-select rounded-0 " name="provinces"
                                                            id="provinces">
                                                            <option value="">Select Provinces</option>
                                                        </select>
                                                        @error('provinces')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        <select class="custom-select rounded-0 my-4" name="municipalities"
                                                            id="municipalities">
                                                            <option value="">Select Municipalities</option>
                                                        </select>
                                                        @error('municipalities')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                        <select class="custom-select rounded-0" name="barangays" id="barangays">
                                                            <option value="">Select Barangay</option>
                                                        </select>
                                                        @error('barangays')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
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
                    @endif
                @endauth
            </section>
    </div><!-- /.container-fluid -->

    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
@endsection
