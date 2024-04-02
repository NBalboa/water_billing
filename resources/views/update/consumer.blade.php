@extends('admin')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h3>Update Consumer</h3>
            </div>
            <section class="content">
                <form method="POST" action="/admin/consumer/edit/{{ $consumer->id }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                placeholder="First Name" value="{{ $consumer->first_name }}">
                            @error('first_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                placeholder="Last Name" value="{{ $consumer->last_name }}">
                            @error('last_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone_no">Phone No.</label>
                            <input type="number" class="form-control" id="phone_no" name="phone_no"
                                placeholder="Phone No." value="{{ $consumer->phone_no }}">
                            @error('phone_no')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Address</label>

                            @php
                                $address = $consumer->address;
                                $split_address = explode(',', $address);
                                $trim_address = array_map('trim', $split_address);
                                // dd($trim_address);
                            @endphp
                            <div class="form-group">
                                <input type="text" class="form-control mb-4" value="{{ $trim_address[0] }}"
                                    id="phone_no" name="street" placeholder="House No./Street/Purok">
                                @error('street')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <select class="custom-select rounded-0 " name="provinces" id="provinces">
                                    <option value="{{ $trim_address[3] }}">{{ $trim_address[3] }}</option>
                                </select>
                                @error('provinces')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <select class="custom-select rounded-0 my-4" name="municipalities" id="municipalities">
                                    <option value="{{ $trim_address[2] }}">{{ $trim_address[2] }}</option>
                                </select>
                                @error('municipalities')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <select class="custom-select rounded-0" name="barangays" id="barangays">
                                    <option value="{{ $trim_address[1] }}">{{ $trim_address[1] }}</option>
                                </select>
                                @error('barangays')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </form>
            </section>
        </section>
    </div>
@endsection
