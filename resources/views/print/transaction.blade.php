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

    #content {
        max-width: 400px;
        margin: 0 auto
    }

    #title {
        text-align: center;
    }


    .payment-details {
        display: flex;
        justify-content: space-between;
        flex-direction: row
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

    .payment_title {
        font-size: 16px;
        margin-bottom: 8px
    }

    .payment_info {
        font-size: 16px;
        font-weight: bolder;
        margin-bottom: 8px
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
</style>

<body>
    @if ($transaction->billing->consumer)
        <section id="content">
            <h2 id="title">Water Billing Management System</h2>
            <h3 style="text-align: center; margin-top: 4px">Vincenzo Sagun</h3>
            <h4 style="text-align: center; margin-top: 4px">Zamboanga Del Sur</h4>
            <h1 style="text-align: center; margin: 24px 0">WATER BILL</h1>
            @php
                $reading_date = Carbon\Carbon::parse($transaction->billing->reading_date)->setTimezone('Asia/Manila');
                $due_date = Carbon\Carbon::parse($transaction->billing->due_date)->setTimezone('Asia/Manila');
                $paid_at = Carbon\Carbon::parse($transaction->billing->billing)->setTimezone('Asia/Manila');
            @endphp

            @php
                $reading_date = Carbon\Carbon::parse($transaction->billing->reading_date);
                $current_date = Carbon\Carbon::now()->setTimezone('Asia/Manila');
                if ($transaction->billing->status == 'PAID') {
                    $current_date = Carbon\Carbon::parse($transaction->billing->paid_at);
                }
                $result = $reading_date->diffInWeeks($current_date);
                $payment = $transaction->billing->price;
                if ($result >= 1) {
                    if ($result > 8) {
                        $result = 8;
                    }
                    $payment = $transaction->billing->price + intval($result) * 50;
                }
            @endphp
            <p style="margin-bottom: 8px"><span style="font-weight: bold">Transaction No: </span>
                {{ sprintf('%07d', $transaction->id) }}</p>
            <p style="margin-bottom: 8px"><span style="font-weight: bold">Bill No: </span>
                {{ sprintf('%07d', $transaction->billing->id) }}</p>
            <p style="margin-bottom: 8px"><span style="font-weight: bold">Date Created:</span>
                {{ $reading_date->format('F j, Y g:i A') }}</p>
            <p style="margin-bottom: 8px"><span style="font-weight: bold">Due
                    Date:</span>
                {{ $due_date->format('F j, Y g:i A') }}</p>
            <p style="margin-bottom: 8px"><span style="font-weight: bold">Paid:</span>
                {{ $paid_at->format('F j, Y g:i A') }}</p>
            <p style="margin-bottom: 8px"><span style="font-weight: bold">
                    Collector:</span>
                {{ $transaction->billing->collector->first_name }} {{ $transaction->billing->collector->last_name }}</p>
            <p style="padding-bottom: 24px; border-bottom: black solid 2px"><span
                    style="font-weight: bold">Cashier:</span>
                {{ $transaction->cashier->first_name }} {{ $transaction->cashier->last_name }}</p>
            <div id="print_content" style="margin-top: 24px">
                <div>
                    <div class="payment-details">
                        <span class="payment_title">Acount No:</span>
                        <span class="payment_info">{{ sprintf('%07d', $transaction->billing->consumer->id) }}</span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Account Name:</span>
                        <span class="payment_info">{{ $transaction->billing->consumer->first_name }}
                            {{ $transaction->billing->consumer->last_name }}</span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Address: </span>
                        <span class="payment_info">{{ $transaction->billing->consumer->street }},
                            {{ $transaction->billing->consumer->barangay }}
                        </span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Meter Code: </span>
                        <span class="payment_info">{{ $transaction->billing->consumer->meter_code }}</span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Previous</span>
                        <span class="payment_info">{{ $transaction->billing->previos }}</span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Current</span>
                        <span class="payment_info">{{ $transaction->billing->current }}</span>
                    </div>
                    <div class="payment-details consumption">
                        <span class="payment_title">Total Consumption</span>
                        <span class="payment_info">{{ $transaction->billing->total_consumption }}</span>
                    </div>

                    <div class="payment-details">
                        <span class="payment_title">Water Bill</span>
                        <span class="payment_info">{{ $transaction->billing->price }}</span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Source Charge</span>
                        <span class="payment_info">20</span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Total Amount Due</span>
                        <span class="payment_info">{{ $transaction->billing->total }}</span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Penalty After Due</span>
                        <span class="payment_info">{{ intval($result) === 0 ? 50 : intval($result) * 50 }}</span>
                    </div>
                    <div class="payment-details"
                        style="border-bottom: 2px solid black; padding-bottom: 8px; margin-bottom: 12px">
                        <span class="payment_title">Total Amount After Due</span>
                        {{-- <span class="payment_info">{{ $transaction->billing->after_due }}</span> --}}
                        @if (intval($result) === 0)
                            <span class="payment_info"> {{ $transaction->billing->after_due }}
                            </span>
                        @else
                            <span class="payment_info">{{ number_format($payment, 2) }}
                            </span>
                        @endif
                    </div>

                    <div class="payment-details">
                        <span class="payment_title">Money</span>
                        <span class="payment_info">{{ $transaction->billing->money }}</span>
                    </div>
                    <div class="payment-details">
                        <span class="payment_title">Change</span>
                        <span class="payment_info">{{ $transaction->billing->change }}</span>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <div id="printBtn">
        <button onclick="window.print()" class="printBtn">Print</button>
        @auth
            @if (auth()->user()->status == 0)
                <a href="/all/transactions" id="back">Back</a>
            @else
                @if (auth()->user()->status == 1)
                    <a href="/consumer" id="back">Back</a>
                @else
                    <a href="/billing/invoice" id="back">Back</a>
                @endif
            @endif
        @endauth
    </div>
    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>

</html>
