@extends('admin')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <p>Reports</p>
                <div>
                    <form method="GET" action="/all/billings">
                        <div class="d-flex  align-items-center">
                            <label for="month">Month</label>
                            <div class="form-group mr-3 align-middle">
                                <select name="month" value="{{ old('month') }} " class="mt-2">
                                    <option value="">Select Year</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">
                                            {{ $month = date('F', mktime(0, 0, 0, $i, 1, date('Y'))) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <label for="year" class="mt-3">Year</label>
                                <select name="year" class="mt-3">
                                    <option value="">Select Year</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->year }}">{{ $year->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <input type="radio" name="status" id="all" checked />
                                <label for="all" class="mr-2">All</label>

                                <input type="radio" name="status" value="pending" id="pending" />
                                <label for="pending " class="mr-2">Pending</label>

                                <input type="radio" name="status" value="paid" id="paid" />
                                <label for="paid" class="mr-2">Paid</label>
                            </div>
                            <div class="form-group mr-2 mt-2">
                                <input type="text" name="search" placeholder="Search ..." id="billing_search" />
                            </div>

                            <div class="form-group mt-2">
                                <input type="submit" value="Search" class="btn btn-info">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <section class="content">
                @if (count($disconnections) > 0)
                    <a href="/billing/disconnections/print" class="btn btn-danger mb-2">Print Disconnection</a>
                @endif
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>Meter No.</th>
                                    <th>Billing No.</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Last Month Paid</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody id="billing_result">
                                @if ($billings->isEmpty())
                                    <div>
                                        <p>No Reports Found</p>
                                    </div>
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

                                            $latest_date_paid = App\Models\Billing::where(
                                                'consumer_id',
                                                $billing->consumer->id,
                                            )
                                                ->whereNotNull('paid_at')
                                                ->where('status', 'PAID')
                                                ->latest()
                                                ->first();
                                        @endphp
                                        <tr class=
                                        "text clickable-tr {{ intval($result) >= 8 && $billing->status === 'PENDING' ? 'text-danger' : 'text-dark' }}"
                                            data-href="{{ $billing->status == 'PENDING' ? "/billing/print/$billing->id" : "/transaction/print/$billing->id" }}"
                                            style="cursor: pointer;">
                                            <td>{{ $billing->consumer->meter_code }}</td>
                                            <td>{{ sprintf('%07d', $billing->id) }}</td>
                                            <td>{{ $billing->consumer->first_name }}
                                                {{ $billing->consumer->last_name }}</td>
                                            <td>{{ $billing->consumer->street }},
                                                {{ $billing->consumer->barangay }}</td>
                                            <td>{{ !$latest_date_paid ? '' : Carbon\Carbon::parse($latest_date_paid->paid_at)->format('F') }}
                                            </td>
                                            <td>{{ intval($result) === 0 ? $billing->total : number_format($payment, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>

                @if (method_exists($billings, 'links'))
                    <div class="d-flex justify-content-center">
                        {{ $billings->links('pagination::bootstrap-4') }}
                    </div>
                @endif

            </section>
        </section>
    </div>
@endsection
