<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>
        Invoice
    </title>

    <style>

        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

    </style>

</head>

<body>

    <h1>
        VELOURE BEAUTY HOUSE
    </h1>

    <h2>
        Invoice
    </h2>

    <table>

        <tr>
            <td>Invoice Number</td>
            <td>{{ $invoice->invoice_number }}</td>
        </tr>

        <tr>
            <td>Customer</td>
            <td>{{ $invoice->customer?->name }}</td>
        </tr>

        <tr>
            <td>Booking</td>
            <td>{{ $invoice->booking?->booking_number }}</td>
        </tr>

        <tr>
            <td>Subtotal</td>
            <td>
                Rp
                {{ number_format(
                    $invoice->subtotal
                ) }}
            </td>
        </tr>

        <tr>
            <td>Discount</td>
            <td>
                Rp
                {{ number_format(
                    $invoice->discount
                ) }}
            </td>
        </tr>

        <tr>
            <td>Grand Total</td>
            <td>
                Rp
                {{ number_format(
                    $invoice->grand_total
                ) }}
            </td>
        </tr>

        <tr>
            <td>Payment Status</td>
            <td>
                {{ strtoupper(
                    $invoice->payment_status
                ) }}
            </td>
        </tr>

    </table>

</body>

</html>