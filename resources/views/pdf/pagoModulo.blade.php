<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0; padding: 0;
            background-color: #f4f4f4;
            height: 100vh; overflow: hidden; /* Evita desborde */
        }
        .invoice-card {
            background: white;
            width: 180mm; height: 260mm; /* Un poco menos que A4 para seguridad */
            margin: 10mm auto;
            padding: 15mm;
            border-radius: 5px;
            position: relative;
        }
        .blue-bar {
            background: #003366; color: white;
            padding: 8px 15px; font-weight: bold;
            margin: 15px 0; font-size: 10pt;
        }
        .header-table { width: 100%; border-bottom: 2px solid #003366; padding-bottom: 10px; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .details-table th { text-align: left; background: #eee; padding: 8px; border: 1px solid #ddd; }
        .details-table td { padding: 8px; border: 1px solid #ddd; }
        .total-box { 
            float: right; width: 200px; 
            margin-top: 20px; text-align: right; 
            border: 2px solid #003366; padding: 10px;
        }
        .footer { 
            position: absolute; bottom: 15mm; left: 15mm; right: 15mm;
            border-top: 1px dashed #ccc; padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-card">
        <table class="header-table">
            <tr>
                <td><img src="{{ public_path('LOGO.jpg') }}" width="120"></td>
                <td style="text-align: right;">
                    <h2 style="color: #003366; margin: 0;">{{ $title }}</h2>
                    <p style="margin: 5px 0;">Recibo: #{{ $pago->id }}</p>
                </td>
            </tr>
        </table>

        <div class="blue-bar">DATOS DEL ESTUDIANTE</div>
        <p><strong>Nombre:</strong> {{ $pago->nombre_completo }}<br>
           <strong>DNI/CE:</strong> {{ $pago->numero_documento }}</p>

        <div class="blue-bar">INFORMACIÓN DEL PROGRAMA</div>
        <p><strong>Programa:</strong> {{ $pago->nombre_programa }}</p>

        <div class="blue-bar">DETALLE DEL PAGO</div>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Concepto / Descripción del Módulo</th>
                    <th style="text-align: right;">Importe</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $pago->concepto }}</td>
                    <td style="text-align: right;">S/. {{ number_format($pago->monto_pagado, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-box">
            <small>PAGADO CON: {{ $pago->nombre_metodopago }}</small><br>
            <span style="font-size: 14pt; font-weight: bold; color: #003366;">
                TOTAL: S/. {{ number_format($pago->monto_pagado, 2) }}
            </span>
        </div>

        <div class="footer">
            <table style="width: 100%;">
                <tr>
                    <td width="100">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="90">
                    </td>
                    <td style="vertical-align: middle;">
                        <small>Este es un documento oficial de IPEDE. Verifique su autenticidad escaneando el código QR. Fecha de pago: {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</small>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>