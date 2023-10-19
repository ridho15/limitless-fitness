<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice #{{ sprintf('%08d', $orderPaket->id) }}</title>

    <style>
        /*
  Common invoice styles. These styles will work in a browser or using the HTML
  to PDF anvil endpoint.
*/

        body {
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr td {
            padding: 0;
        }

        table tr td:last-child {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .large {
            font-size: 1.5em;
        }

        .total {
            font-weight: bold;
            color: #396552;
        }

        .logo-container {
            margin: 20px 0 70px 0;
        }

        .invoice-info-container {
            font-size: 0.875em;
        }

        .invoice-info-container td {
            padding: 4px 0;
        }

        .client-name {
            font-size: 1.5em;
            vertical-align: top;
        }

        .line-items-container {
            margin: 50px 0;
            font-size: 0.875em;
        }

        .line-items-container th {
            text-align: left;
            color: #999;
            border-bottom: 2px solid #ddd;
            padding: 10px 0 15px 0;
            font-size: 0.75em;
            text-transform: uppercase;
        }

        .line-items-container th:last-child {
            text-align: right;
        }

        .line-items-container td {
            padding: 15px 0;
        }

        .line-items-container tbody tr:first-child td {
            padding-top: 25px;
        }

        .line-items-container.has-bottom-border tbody tr:last-child td {
            padding-bottom: 25px;
            border-bottom: 2px solid #ddd;
        }

        .line-items-container.has-bottom-border {
            margin-bottom: 0;
        }

        .line-items-container th.heading-quantity {
            width: 50px;
        }

        .line-items-container th.heading-price {
            text-align: right;
            width: 100px;
        }

        .line-items-container th.heading-subtotal {
            width: 100px;
        }

        .payment-info {
            width: 20%;
            font-size: 0.75em;
            line-height: 1.5;
        }

        .footer {
            margin-top: 100px;
        }

        .footer-thanks {
            font-size: 1.125em;
        }

        .footer-thanks img {
            display: inline-block;
            position: relative;
            top: 1px;
            width: 16px;
            margin-right: 4px;
        }

        .footer-info {
            float: right;
            margin-top: 5px;
            font-size: 0.75em;
            color: #ccc;
        }

        .footer-info span {
            padding: 0 5px;
            color: black;
        }

        .footer-info span:last-child {
            padding-right: 0;
        }

        .page-container {
            display: none;
        }

        /*
  The styles here for use when generating a PDF invoice with the HTML code.

  * Set up a repeating page counter
  * Place the .footer-info in the last page's footer
*/

        .footer {
            margin-top: 30px;
        }

        .footer-info {
            float: none;
            position: running(footer);
            margin-top: -25px;
        }

        .page-container {
            display: block;
            position: running(pageContainer);
            margin-top: -25px;
            font-size: 12px;
            text-align: right;
            color: #999;
        }

        .page-container .page::after {
            content: counter(page);
        }

        .page-container .pages::after {
            content: counter(pages);
        }


        @page {
            @bottom-right {
                content: element(pageContainer);
            }

            @bottom-left {
                content: element(footer);
            }
        }
    </style>
</head>

<body>
    <div class="logo-container">
        {{-- <img style="height: 50px" src="{{ asset('assets/images/logo.png') }}"> --}}
    </div>

    <table class="invoice-info-container">
        <tr>
            <td rowspan="2" class="client-name">
                Client Name
            </td>
            <td>
                {{ $orderPaket->user->name }}
            </td>
        </tr>
        <tr>
            <td>
                {{ $orderPaket->user->alamat }}
            </td>
        </tr>

        <tr>
            <td>
                Invoice Date: <strong>{{ \Carbon\Carbon::parse($orderPaket->created_at)->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</strong>
            </td>
            <td>
                {{ $orderPaket->user->negara }}
            </td>
        </tr>
        <tr>
            <td>
                Invoice No: <strong>#{{ sprintf('%08d', $orderPaket->id) }}</strong>
            </td>
            <td>
                {{ $orderPaket->user->email }}
            </td>
        </tr>
    </table>


    <table class="line-items-container">
        <thead>
            <tr>
                <th class="heading-quantity">Qty</th>
                <th class="heading-description">Description</th>
                <th class="heading-price">Price</th>
                <th class="heading-subtotal">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $orderPaket->paket->nama }}</td>
                <td class="right">Rp.{{ number_format($orderPaket->paket->harga,0,',','.') }}</td>
                <td class="bold">Rp.{{ number_format($orderPaket->paket->harga,0,',','.') }}</td>
            </tr>
        </tbody>
    </table>


    <table class="line-items-container has-bottom-border">
        <thead>
            <tr>
                <th style="width: 20%">Payment Info</th>
                <th>Due By</th>
                <th>Discount | Total Due</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="payment-info">
                    <div>
                        Account No: <strong>{{ sprintf('%08d', $orderPaket->user->id) }}</strong>
                    </div>
                    <div>
                        Invoice No: <strong>#{{ sprintf('%08d', $orderPaket->id) }}</strong>
                    </div>
                </td>
                <td class="large">{{ Carbon\Carbon::parse($orderPaket->tanggal_pembayaran)->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</td>
                <td class="large total">{{ $orderPaket->paket->diskon }}% | Rp.{{ number_format($orderPaket->harga,0,',','.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-info">
            <span>dailydosepilates@gmail.com </span> |
            <span>081212327038 </span> |
            <span>{{ config('app.url') }}</span>
        </div>
    </div>

</body>

</html>
