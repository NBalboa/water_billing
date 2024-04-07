@extends('admin')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h3>User Update</h3>
            </div>
            <section class="content">

                @if ($user->status == 1)
                    <form method="POST" action="/user/edit/{{ $user->id }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="First Name"
                                    value="{{ old('first_name') == null ? $user->first_name : old('first_name') }}">
                                @error('first_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="Last Name"
                                    value="{{ old('last_name') == null ? $user->last_name : old('last_name') }}">
                                @error('last_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control mb-4" value="{{ $user->street }}" id="phone_no"
                                    name="street" placeholder="House No./Street/Purok">
                                @error('street')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <select class="custom-select rounded-0 " name="barangay">
                                    <option value="{{ $user->barangay }}">{{ $user->barangay }}</option>
                                    @foreach ($areas as $area)
                                        @if ($user->barangay != $area->name)
                                            <option value="{{ $area->name }}">{{ $area->name }}</option>
                                        @endif
                                    @endforeach

                                </select>
                                @error('barangay')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Assign</label>
                                @error('assign_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <select class="custom-select rounded-0 " name="assign_id">
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @foreach ($areas as $area)
                                        @if ($area->id !== $user->assign_id)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endif
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="phone_no">Phone No.</label>
                                <input type="number" class="form-control"
                                    value="{{ old('phone_no') == null ? $user->phone_no : old('phone_no') }}"
                                    id="phone_no" name="phone_no" placeholder="Phone No.">
                                @error('phone_no')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="custom-select rounded-0 " name="status">
                                    @if ($user->status == 0)
                                        <option value="0">Admin</option>
                                    @else
                                        @if ($user->status == 1)
                                            <option value="1">Collector</option>
                                        @else
                                            <option value="2">Cashier</option>
                                        @endif
                                    @endif

                                    @if ($user->status == 0)
                                        <option value="1">Colllector</option>
                                        <option value="2">Cashier</option>
                                    @else
                                        @if ($user->status == 1)
                                            <option value="0">Admin</option>
                                            <option value="2">Cashier</option>
                                        @else
                                            <option value="0">Admin</option>
                                            <option value="1">Collector</option>
                                        @endif
                                    @endif
                                </select>
                                @error('status')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                    </form>
                @else
                    <form method="POST" action="/user/edit/{{ $user->id }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="First Name"
                                    value="{{ old('first_name') == null ? $user->first_name : old('first_name') }}">
                                @error('first_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="Last Name"
                                    value="{{ old('last_name') == null ? $user->last_name : old('last_name') }}">
                                @error('last_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control mb-4" value="{{ $user->street }}" id="phone_no"
                                    name="street" placeholder="House No./Street/Purok">
                                @error('street')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <select class="custom-select rounded-0 " name="barangay">
                                    <option value="{{ $user->barangay }}">{{ $user->barangay }}</option>
                                    <option value="Barangay Kapatagan">Barangay Kapatagan</option>
                                    <option value="Barangay Biu-os">Barangay Biu-os</option>
                                    <option value="Barangay Danan">Barangay Danan</option>
                                </select>
                                @error('provinces')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone_no">Phone No.</label>
                                <input type="number" class="form-control"
                                    value="{{ old('phone_no') == null ? $user->phone_no : old('phone_no') }}"
                                    id="phone_no" name="phone_no" placeholder="Phone No.">
                                @error('phone_no')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="custom-select rounded-0 " name="status">
                                    @if ($user->status == 0)
                                        <option value="0">Admin</option>
                                    @else
                                        @if ($user->status == 1)
                                            <option value="1">Collector</option>
                                        @else
                                            <option value="2">Cashier</option>
                                        @endif
                                    @endif

                                    @if ($user->status == 0)
                                        <option value="1">Colllector</option>
                                        <option value="2">Cashier</option>
                                    @else
                                        @if ($user->status == 1)
                                            <option value="0">Admin</option>
                                            <option value="2">Cashier</option>
                                        @else
                                            <option value="0">Admin</option>
                                            <option value="1">Collector</option>
                                        @endif
                                    @endif
                                </select>
                                @error('status')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                    </form>
                @endif


            </section>
        </section>
    </div>
@endsection
