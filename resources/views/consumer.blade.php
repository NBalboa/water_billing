@extends('admin')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <section class="content">
                <div class="mb-3">
                    <form method="GET" action="#">
                        <div class="input-group input-group-sm" style="width: 100%;">
                            <input type="text" name="table_search" id="consumer_search" class="form-control float-right"
                                placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row" id="consumer_result">

                    @if ($consumers->isEmpty())
                        <p>No Consumer Yet</p>
                    @else
                        {{-- <p>Not Empty</p> --}}
                        @foreach ($consumers as $consumer)
                            <div class="col-md-3">

                                <div class="card card-primary ">

                                    <div class="card-header bg-primary">
                                        <a href="/consumer/{{ $consumer->id }}">
                                            <h3 class="card-title text-white ">Consumer</h3>
                                        </a>


                                    </div>
                                    <div class="card-body">
                                        <a href="/consumer/{{ $consumer->id }}">
                                            <p class="text-dark"><span class="font-weight-bold">Meter Code : </span>
                                                {{ $consumer->meter_code }}
                                            </p>
                                        </a>
                                        <a href="/consumer/{{ $consumer->id }}">
                                            <p class="text-dark">
                                                <span class="font-weight-bold text-dark">Consumer Name : </span>
                                                {{-- <a href="/consumer/{{ $consumer->id }} " class="text-dark"> --}}
                                                {{ $consumer->first_name }} {{ $consumer->last_name }}
                                                {{-- </a> --}}
                                            </p>
                                        </a>
                                        <a href="/consumer/{{ $consumer->id }}">

                                            <p class="text-dark"><span class="font-weight-bold">Phono No :
                                                </span>{{ $consumer->phone_no }}
                                            </p>
                                        </a>
                                        <a href="/consumer/{{ $consumer->id }}">
                                            <p class="text-dark"><span class="font-weight-bold">Address :
                                                </span>{{ $consumer->street }},
                                                {{ $consumer->barangay }}</p>
                                        </a>

                                    </div>
                                    <!-- /.card-body -->
                                    {{-- </a> --}}

                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>

                @if (method_exists($consumers, 'links'))
                    <div class="d-flex justify-content-center">
                        {{ $consumers->links('pagination::bootstrap-4') }}
                    </div>
                @endif
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
                                                        <select class="custom-select rounded-0 " name="barangay">
                                                            <option value="">Select Barangay</option>
                                                            <option value="Barangay Kapatagan">Barangay Kapatagan</option>
                                                            <option value="Barangay Biu-os">Barangay Biu-os</option>
                                                            <option value="Barangay Danan">Barangay Danan</option>
                                                        </select>
                                                        @error('barangay')
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
