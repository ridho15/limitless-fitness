<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        /* @font-face {
            font-family: SourceSansPro;
            src: url("{{ asset('/font/SourceSansPro-Regular.ttf') }}");
        } */

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-family: SourceSansPro;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #logo img {
            height: 70px;
        }

        #company {
            float: right;
            text-align: right;
        }


        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #396552;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #396552;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 20px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3 {
            color: #396552;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #396552;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {}

        table .total {
            background: #396552;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 10px 20px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #396552;
            font-size: 1.4em;
            border-top: 1px solid #396552;

        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #396552;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            {{-- <img src="{{ asset('assets/images/logo.png') }}"> --}}
        </div>
        <div id="company">
            <h2 class="name">Daily Dose Pilates</h2>
            <div style="width: 50%; float: left;">{{ $orderPaket->location->nama }} - {{ $orderPaket->location->alamat }}</div>
            <div>{{ $orderPaket->location->no_hp }}</div>
            <div><a href="mailto:{{ $orderPaket->location->email }}">{{ $orderPaket->location->email }}</a></div>
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">INVOICE TO:</div>
                <h2 class="name">{{ $orderPaket->user->name }}</h2>
                <div class="address">{{ $orderPaket->user->alamat }}</div>
                <div class="email"><a href="mailto:{{ $orderPaket->user->email }}">{{ $orderPaket->user->email }}/</a>
                </div>
            </div>
            <div id="invoice">
                <h1>INVOICE #{{ sprintf('%08d', $orderPaket->id) }}({{ $orderPaket->unix_code }})</h1>
                <div class="date">Date of Invoice: {{ date('d-m-Y', strtotime($orderPaket->created_at)) }}</div>
                <div class="date">Due Date: {{ date('d-m-Y', strtotime($orderPaket->expired_date)) }}</div>
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">PAKET</th>
                    <th class="unit">HARGA</th>
                    <th class="qty">QUANTITY</th>
                    <th class="total">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="no">01</td>
                    <td class="desc">
                        {{ $orderPaket->paket->nama }}
                    </td>
                    <td class="unit">Rp.{{ number_format($orderPaket->paket->harga,0,',','.') }}</td>
                    <td class="qty">1</td>
                    <td class="total">Rp.{{ number_format($orderPaket->paket->harga,0,',','.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                @php
                $potongan = $orderPaket->voucherCustomer->voucher->disc_harga +
                ($orderPaket->voucherCustomer->voucher->disc_percent * $orderPaket->paket->harga / 100);
                @endphp
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2">SUBTOTAL</td>
                    <td>Rp.{{ number_format($orderPaket->paket->harga,0,',','.') }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2">TAX</td>
                    <td>Rp.{{ number_format($orderPaket->unix_code,0,',','.') }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2">Potongan</td>
                    <td>Rp.{{ number_format($potongan,0,',','.') }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2">GRAND TOTAL</td>
                    <td>Rp.{{ number_format($orderPaket->harga,0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
        <div id="thanks">Thank you!</div>
        <div id="notices">
            <div>NOTICE:</div>
            <div class="notice">Segera lakukan pembayaran sebelum jangka waktu yang sudah di tentukan</div>
        </div>
    </main>
    <footer>
        Invoice dibuat di komputer dan sah tanpa tanda tangan dan stempel.
    </footer>
</body>

</html>