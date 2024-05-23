<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }


    .zui-table {
        border: solid 1px #DDEEEE;
        border-collapse: collapse;
        border-spacing: 0;
        font: normal 13px Arial, sans-serif;
        width: 100%;

    }


    .zui-table thead th {
        background-color: #DDEFEF;
        border: solid 1px #DDEEEE;
        color: #336B6B;
        padding: 10px;
        text-align: left;
        text-shadow: 1px 1px 1px #fff;
    }

    .zui-table tbody td {
        border: solid 1px #DDEEEE;
        color: #333;
        padding: 10px;
        text-shadow: 1px 1px 1px #fff;
        margin: 0 auto;
    }







    .printBtn {
        margin-top: 12px;
        cursor: pointer;
        outline: 0;
        display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        text-align: center;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 6px 12px;
        font-size: 1rem;
        border-radius: .25rem;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        color: #0d6efd;
        border-color: #0d6efd;
    }

    .printBtn:hover {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }





    #back {
        display: inline-block;
        outline: 0;
        cursor: pointer;
        padding: 5px 16px;
        font-size: 14px;
        font-weight: 500;
        line-height: 20px;
        vertical-align: middle;
        border: 1px solid;
        border-radius: 6px;
        color: #0366d6;
        background-color: #fafbfc;
        border-color: #1b1f2326;
        box-shadow: rgba(27, 31, 35, 0.04) 0px 1px 0px 0px, rgba(255, 255, 255, 0.25) 0px 1px 0px 0px inset;
        transition: 0.2s cubic-bezier(0.3, 0, 0.5, 1);
        transition-property: color, background-color, border-color;
        text-decoration: none
    }

    #back:hover {
        color: #ffffff;
        background-color: #0366d6;
        border-color: #1b1f2326;
        box-shadow: rgba(27, 31, 35, 0.1) 0px 1px 0px 0px, rgba(255, 255, 255, 0.03) 0px 1px 0px 0px inset;
        transition-duration: 0.1s;
    }

    .consumption {
        border-bottom: 2px solid black;
        border-top: 2px solid black;
        padding: 18px 0;
        margin-bottom: 0;
    }

    #printBtn {
        position: absolute;
        right: 14px;
        top: 14px;
    }

    .consumption .payment_info,
    .consumption .payment_title {
        margin-bottom: 0
    }

    @media print {
        #printBtn {
            display: none;
        }

        #back {
            display: none
        }
    }

    .headings {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 24px;
        text-align: center;
    }

    .headings img {
        width: 150px;
        height: 150px;
    }

    .zui-table caption {
        border: solid 1px #DDEEEE;
        color: black;
        padding: 10px;
        text-align: left;
        text-shadow: 1px 1px 1px #fff;
        text-align: center;
        font-weight: 900;
        font-size: 16px;
    }

    .wrapper {
        margin: 0 24px
    }
</style>

<body>

    <div class="wrapper">
        <div class="headings">
            <img src="/assets/img/image1.png" />
            <div>
                <h1>Water Billing Management System</h1>
                <h2>Vincenzo Sagun</h2>
                <h3>Zamboanga Del Sur</h3>
            </div>
        </div>
        <table class="zui-table">
            <caption>Disconnections Reports</caption>
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
            <tbody>

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

                        $latest_date_paid = App\Models\Billing::where('consumer_id', $billing->consumer->id)
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
            </tbody>
        </table>
    </div>
    <div id="printBtn">
        <button onclick="window.print()" class="printBtn">Print</button>
        @auth
            @if (auth()->user()->status == 0)
                <a href="/all/billings" id="back">Back</a>
            @else
                @if (auth()->user()->status == 1)
                    <a href="/consumer" id="back">Back</a>
                @else
                    <a href="/billing/invoice" id="back">Back</a>
                @endif
            @endif
        @endauth
    </div>

</body>

</html>
