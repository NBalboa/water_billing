@extends('admin')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <p>User Profile</p>
                @session('success')
                    <p class="text-success">{{ session('success') }}</p>
                @endsession
            </div>
            <section class="content">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Details</h3>
                    </div>
                    <div class="card-body">
                        <strong>Username</strong>
                        <p class="text-muted">
                            {{ $user->username }}
                        </p>
                        <hr>
                        <strong>Full Name</strong>
                        <p class="text-muted">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </p>
                        <hr>
                        <strong>Role</strong>
                        @if ($user->role == 0)
                            <p class="text-muted">
                                Admin
                            </p>
                        @endif
                        @if ($user->role == 1)
                            <p class="text-muted">
                                Collector
                            </p>
                        @endif
                        @if ($user->role == 2)
                            <p class="text-muted">
                                Cashier
                            </p>
                        @endif
                        {{-- {{ $consumer->meter_code }} --}}
                        <hr>
                        <strong>Phone No.</strong>
                        <p class="text-muted">{{ $user->phone_no }}</p>
                        @if (auth()->user()->status == 1)
                            <hr>
                            <strong>Assign Area</strong>
                            <p class="text-muted">{{ $user->area->name ?? '' }}</p>
                        @endif
                        <hr>
                        <strong>Address</strong>
                        <p class="text-muted">
                            {{ $user->street }}, {{ $user->barangay }}
                        </p>
                        <hr>
                    </div>
                </div>
            </section>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changePassword">
                Change Password
            </button>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#changeUsername">
                Change Username
            </button>
        </section>



        <div class="modal fade" id="changeUsername" tabindex="-1" aria-labelledby="changeUsernameLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeUsernameLabel">Change Username</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/profile/new/username/{{ auth()->user()->id }}">
                            @csrf
                            <div class="form-group">
                                <label for="username">New Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="{{ old('username' ?? '') }}">
                                @error('username')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="">
                                @error('password')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/profile/new/password/{{ auth()->user()->id }}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="old_password">Old Password</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password"
                                        value="">
                                    @error('old_password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        value="">
                                    @error('new_password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="conf_new_password">Confirm New Password</label>
                                    <input type="password" class="form-control" id="conf_new_password"
                                        name="conf_new_password" value="">
                                    @error('conf_new_password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
