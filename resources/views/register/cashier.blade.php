@extends('admin')


@section('content')
    <!-- Main Sidebar Container -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <p>Register Cashier</p>
                @if (session()->has('success'))
                    <p class="text-success">{{ session('success') }}</p>
                @endif
                <!-- /.col -->
            </div>

            <section class="content">

                {{-- @if (session()->has('success'))
                    <p class="text-success">{{ session('success') }}</p>
                @endif --}}
                <form method="POST" action="/admin/register/cashier">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                value="{{ old('username') }}">
                            @error('username')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror

                        </div>
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
                            <label>Address</label>
                            <input type="text" class="form-control mb-4" value="{{ old('street') }}" id="phone_no"
                                name="street" placeholder="House No./Street/Purok">
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
                        <div class="form-group">
                            <label for="phone_no">Phone No.</label>
                            <input type="number" class="form-control" value="{{ old('phone_no') }}" id="phone_no"
                                name="phone_no" placeholder="Phone No.">
                            @error('phone_no')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="*******">
                            @error('password')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="*******">
                            @error('confirm_password')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </section>
    </div><!-- /.container-fluid -->

    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
@endsection
<!-- Site wrapper -->
