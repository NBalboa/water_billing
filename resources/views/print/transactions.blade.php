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

    body {
        margin: 67px 24px 0 24px
    }



    #printBtn {
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

    #printBtn:hover {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }



    .printBtn {
        position: absolute;
        top: 14px;
        right: 14px;
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

        .printBtn {
            display: none;
        }
    }
</style>

<body>
    {{-- <h1>All</h1> --}}
    <table class="zui-table">
        <thead>
            <tr>
                <th>Meter No.</th>
                <th>Billing No.</th>
                <th>Name</th>
                <th>Address</th>
                <th>Date Paid</th>
                <th>Month Paid</th>
                <th>Cashier Name</th>
            </tr>
        </thead>
        <tbody>
            @if ($transactions->isEmpty())
                <div>
                    <p>No Reports Found</p>
                </div>
            @else
                @foreach ($transactions as $transaction)
                    @php
                        $paid_at = Carbon\Carbon::parse($transaction->billing->paid_at);
                    @endphp
                    <tr class="text clickable-tr " data-href="/transaction/print/{{ $transaction->billing->id }}"
                        style="cursor: pointer;">
                        <td>{{ $transaction->billing->consumer->meter_code }}</td>
                        <td>{{ sprintf('%07d', $transaction->billing->id) }}</td>
                        <td>{{ $transaction->billing->consumer->first_name }}
                            {{ $transaction->billing->consumer->last_name }}</td>
                        <td>{{ $transaction->billing->consumer->street }},
                            {{ $transaction->billing->consumer->barangay }}</td>
                        <td>{{ $paid_at->format('F j, Y') }}
                        </td>
                        <td>{{ $paid_at->format('F') }}
                        </td>
                        <td>{{ $transaction->cashier->first_name }}
                            {{ $transaction->cashier->last_name }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="printBtn">
        <a href="/all/transactions" id="back">Back</a>
        <button id="printBtn" onclick="window.print()">Print</button>
    </div>

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>

</html>
