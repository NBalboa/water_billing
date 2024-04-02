@extends('admin')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h3>Users</h3>
            </div>
            <section class="content">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone No.</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->isEmpty())
                                <tr>
                                    <td>No Consumer Yet</td>
                                </tr>
                            @else
                                {{-- <p>Not Empty</p> --}}
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->first_name }} </td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->phone_no }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>

                                            @auth
                                                @if (auth()->user()->status == 0)
                                                    <form action="/user/delete/{{ $user->id }}" method="POST"
                                                        style="display: inline-block">
                                                        @csrf
                                                        <button class="btn btn-danger" type="submit">Delete</button>
                                                    </form>
                                                    <a href="/user/edit/{{ $user->id }}" class="btn btn-default">Edit</a>
                                                @endif
                                            @endauth
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </div>
@endsection
