@extends('admin')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <p>Reports</p>
                <div>
                    <form method="GET" action="/all/billings">
                        <div class="d-flex  align-items-center">
                            <div class="form-group mr-3">
                                <label for="month">Month</label>
                                <select name="month" value="{{ old('month') }}">
                                    <option value="">Select Year</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">
                                            {{ $month = date('F', mktime(0, 0, 0, $i, 1, date('Y'))) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <label for="year">Year</label>
                                <select name="year">
                                    <option value="">Select Year</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->year }}">{{ $year->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="radio" name="status" id="all" checked />
                                <label for="all" class="mr-2">All</label>

                                <input type="radio" name="status" value="pending" id="pending" />
                                <label for="pending " class="mr-2">Pending</label>

                                <input type="radio" name="status" value="paid" id="paid" />
                                <label for="paid" class="mr-2">Paid</label>
                            </div>
                            <div class="form-group mr-2">
                                <input type="text" name="search" placeholder="Search ..." id="billing_search" />
                            </div>

                            <div class="form-group">
                                <input type="submit" value="Search" class="btn btn-info">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Reports</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Bill No.</th>
                                    <th>Created At</th>
                                    <th>Meter Code</th>
                                    <th>Consumer Name</th>
                                    <th>Status</th>
                                    <th>Total Consumption</th>
                                    <th>Total Amount Due</th>
                                    <th>Total Amount After Due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="billing_result">
                                @if ($billings->isEmpty())
                                    <tr>
                                        <td>No Reports Found</td>
                                    </tr>
                                @else
                                    @foreach ($billings as $billing)
                                        @php
                                            $reading_date = Carbon\Carbon::parse($billing->reading_date);
                                            $current_date = Carbon\Carbon::now()->setTimezone('Asia/Manila');
                                            if ($billing->status == 'PAID') {
                                                $current_date = Carbon\Carbon::parse($billing->paid_at);
                                            }
                                            $result = $reading_date->diffInWeeks($current_date);
                                            $payment = $billing->price;
                                            if ($result >= 1) {
                                                if ($result > 8) {
                                                    $result = 8;
                                                }
                                                $payment = $billing->price + intval($result) * 50;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ sprintf('%07d', $billing->id) }}</td>
                                            <td>{{ $reading_date->format('F j, Y g:i A') }}</td>
                                            <td>{{ $billing->consumer->meter_code }}</td>
                                            <td class="{{ intval($result) >= 8 ? 'text-danger' : '' }}">
                                                {{ $billing->consumer->first_name }}
                                                {{ $billing->consumer->last_name }}</td>
                                            <td>{{ $billing->status }}</td>
                                            <td>{{ $billing->total_consumption }}</td>
                                            <td>{{ $billing->total }}</td>
                                            @if (intval($result) === 0)
                                                <td>{{ $billing->after_due }}</td>
                                            @else
                                                <td>{{ number_format($payment, 2) }}</td>
                                            @endif
                                            <td>
                                                <a class="btn btn-dark" href="/billing/print/{{ $billing->id }}">Print</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
                @if (method_exists($billings, 'links'))
                    <div class="d-flex justify-content-center">
                        {{ $billings->links('pagination::bootstrap-4') }}
                    </div>
                @endif
                {{-- @php
                    $month = request()->query('month');
                    $year = request()->query('year');
                    $status = request()->query('status');
                    $search = request()->query('search');
                @endphp
                <div class="text-right">
                    <a href="/billings/print/receipts/{{ $month == null ? 'blank' : $month }}/{{ $year == null ? 'blank' : $year }}/{{ $status == null ? 'blank' : $status }}/{{ $search == null ? 'blank' : $search }}"
                        class="btn btn-dark">Generate Receipts</a>
                </div> --}}
            </section>
        </section>
    </div>
@endsection
