@php
$title = $dataPDF['title'];
$pago = $dataPDF['pago'];
$empresa = $dataPDF['empresa'];
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 1cm; }
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
        }
        /* Layout con tablas para evitar problemas de Flexbox */
        .table-layout {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }
        .header-logo { width: 150px; }
        .header-info { text-align: right; }
        
        .header-info h1 {
            color: #0056b3;
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
        }
        .text-blue { color: #0056b3; font-weight: bold; }
        
        .section-title {
            background-color: #0056b3;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            margin-top: 20px;
            border-radius: 3px;
        }

        .info-box {
            padding: 10px;
            border: 1px solid #eee;
            margin-bottom: 15px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .details-table th {
            background-color: #f2f2f2;
            border-bottom: 2px solid #0056b3;
            padding: 8px;
            text-align: left;
        }
        .details-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .total-container {
            margin-top: 15px;
            text-align: right;
        }
        .total-amount {
            font-size: 14px;
            background: #f9f9f9;
            padding: 10px;
            display: inline-block;
            border: 1px solid #ddd;
        }

        .footer-table {
            width: 100%;
            margin-top: 50px;
            vertical-align: bottom;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 5px;
            margin-left: auto;
        }
    </style>
</head>
<body>
    <table class="table-layout">
        <tr>
            <td class="header-logo">
                {{-- Se recomienda usar la ruta física o base64 para evitar problemas de carga --}}
                <img src="{{ public_path('LOGO.jpg') }}" style="width: 120px;">
            </td>
            <td class="header-info">
                <h1>{{ $title }}</h1>
                <p><strong>Folio:</strong> <span class="text-blue">#{{ $pago->id }}</span><br>
                <strong>Fecha de Emisión:</strong> {{ \Carbon\Carbon::parse($pago->fecha_matricula)->format('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    <div style="margin-top: 10px;">
        <small><strong>{{ $empresa['razon_social'] }}</strong> | RUC: {{ $empresa['ruc'] }}</small>
    </div>

    <div class="section-title">INFORMACIÓN DEL ALUMNO</div>
    <div class="info-box">
        <table class="table-layout">
            <tr>
                <td width="50%"><strong>N° DOCUMENTO:</strong> {{ $pago->numero_documento }}</td>
                <td width="50%"><strong>ALUMNO:</strong> {{ $pago->nombre_completo }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">DETALLES DEL MOVIMIENTO</div>
    <table class="details-table">
        <thead>
            <tr>
                <th width="70%">CONCEPTO</th>
                <th width="30%" style="text-align: right;">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $pago->concepto }}</td>
                <td style="text-align: right;">S/. {{ number_format($pago->monto_pagado, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-container">
        <p><strong>Método de Pago:</strong> {{ $pago->nombre_metodopago }}</p>
        <div class="total-amount">
            <strong>TOTAL PAGADO:</strong> <span class="text-blue">S/. {{ number_format($pago->monto_pagado, 2) }}</span>
        </div>
    </div>

    <table class="footer-table">
        <tr>
            <td width="30%">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="100" height="100">
                <br>
                <small style="color: #888;">Escanee para validar el pago</small>
            </td>
            <td width="70%" style="vertical-align: bottom;">
                <div class="signature-line">
                    <strong>FIRMA AUTORIZADA</strong><br>
                    IPEDE - Administración
                </div>
            </td>
        </tr>
    </table>
</body>
</html>