<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Pago N° {{ str_pad($pago->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #334155;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        /* Layout del Encabezado */
        .header-table {
            margin-bottom: 20px;
        }
        .logo-img {
            max-height: 65px;
            max-width: 180px;
            object-fit: contain;
        }
        .inst-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
        }
        .inst-info {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
            line-height: 1.3;
        }

        /* Comprobante Box */
        .doc-box {
            border: 1.5px solid #0284c7;
            background-color: #f0f9ff;
            border-radius: 6px;
            text-align: center;
            padding: 10px;
        }
        .doc-box .ruc {
            font-size: 11px;
            font-weight: bold;
            color: #0369a1;
        }
        .doc-box .title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin: 4px 0;
            text-transform: uppercase;
        }
        .doc-box .number {
            font-size: 14px;
            font-weight: bold;
            color: #e11d48;
        }

        /* Secciones & Cards */
        .section-header {
            font-size: 10.5px;
            font-weight: bold;
            color: #0369a1;
            background-color: #f1f5f9;
            padding: 5px 8px;
            border-left: 3px solid #0284c7;
            margin-top: 12px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 3px 4px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #475569;
            width: 18%;
        }
        .value {
            color: #0f172a;
            width: 32%;
        }

        /* Grid de Items */
        .grid-table {
            margin-top: 10px;
            border: 1px solid #e2e8f0;
        }
        .grid-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
        }
        .grid-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10.5px;
        }

        /* Badges de Forma de Pago */
        .badge-payment {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            background-color: #e0f2fe;
            color: #0369a1;
            text-transform: uppercase;
        }

        /* Totales */
        .totals-table {
            width: 45%;
            margin-left: auto;
            margin-top: 10px;
        }
        .totals-table td {
            padding: 4px 8px;
            text-align: right;
        }
        .row-total {
            background-color: #f0fdf4;
            color: #15803d;
            font-weight: bold;
            font-size: 12px;
            border-top: 1.5px solid #22c55e;
        }

        /* Footer & Firmas */
        .footer-container {
            margin-top: 60px;
        }
        .signature-box {
            width: 200px;
            margin: 0 auto;
            text-align: center;
            border-top: 1px solid #94a3b8;
            padding-top: 4px;
            font-size: 10px;
            color: #475569;
        }
        .footer-text {
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            margin-top: 40px;
            border-top: 1px dashed #e2e8f0;
            padding-top: 8px;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
    </style>
</head>
<body>

    <!-- ENCABEZADO -->
    <table class="header-table">
        <tr>
            <td style="width: 20%; vertical-align: middle;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo">
                @else
                    <div style="font-size: 20px; font-weight: bold; color: #0284c7;">ACADEMIA</div>
                @endif
            </td>
            <td style="width: 48%; padding-left: 10px; vertical-align: middle;">
                <h1 class="inst-title">{{ $institucion->nombre ?? 'SISTEMA DE GESTIÓN ACADÉMICA' }}</h1>
                <div class="inst-info">
                    {{ $institucion->sigla ?? 'Formación Especializada' }}<br>
                    <strong>Dirección:</strong> {{ $institucion->direccion ?? 'Sede Principal' }}<br>
                    <strong>Contacto:</strong> {{ $institucion->telefono_contacto ?? 'N/A' }} | {{ $institucion->email ?? '' }}
                </div>
            </td>
            <td style="width: 32%; vertical-align: middle;">
                <div class="doc-box">
                    <div class="ruc">R.U.C. {{ $institucion->ruc ?? '20000000000' }}</div>
                    <div class="title">Constancia de Pago</div>
                    <div class="number">N° {{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATOS DE LA MATRÍCULA Y ESTUDIANTE -->
    <div class="section-header">Información Académica y del Estudiante</div>
    <table class="data-table">
        <tr>
            <td class="label">N° Matrícula:</td>
            <td class="value"><strong style="color: #0284c7;">#{{ str_pad($matricula->id ?? 0, 4, '0', STR_PAD_LEFT) }}</strong></td>
            <td class="label">Fecha Matrícula:</td>
            <td class="value">{{ isset($matricula->fecha_matricula) ? \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Estudiante:</td>
            <td class="value" colspan="3"><strong>{{ $matricula->persona->nombre_completo ?? 'N/A' }}</strong></td>
        </tr>
        <tr>
            <td class="label">Doc. Identidad:</td>
            <td class="value">{{ $matricula->persona->numero_documento ?? 'N/A' }}</td>
            <td class="label">Programa/Curso:</td>
            <td class="value">{{ $pago->programa->nombre ?? $matricula->programa->nombre ?? 'Carrera / Curso Académico' }}</td>
        </tr>
    </table>

    <!-- DETALLES DE LA TRANSACCIÓN -->
    <div class="section-header">Detalles de la Transacción</div>
    <table class="data-table">
        <tr>
            <td class="label">Fecha de Pago:</td>
            <td class="value">{{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'N/A' }}</td>
            <td class="label">Forma de Pago:</td>
            <td class="value">
                <span class="badge-payment">{{ $pago->formaPago->nombre ?? 'General' }}</span>
            </td>
        </tr>
        <tr>
            <td class="label">N° Operación:</td>
            <td class="value font-mono"><strong>{{ $pago->numero_operacion ?? 'S/N' }}</strong></td>
            <td class="label">Estado:</td>
            <td class="value" style="color: #16a34a; font-weight: bold;">
                {{ $pago->estadoPago->nombre ?? 'Procesado / Pagado' }}
            </td>
        </tr>
    </table>

    <!-- TABLA DETALLE DE PAGO Y DESGLOSE -->
    <div class="section-header">Concepto de Pago y Desglose</div>
    <table class="grid-table">
        <thead>
            <tr>
                <th style="width: 8%;" class="text-center">Item</th>
                <th style="width: 47%;">Descripción del Concepto</th>
                <th style="width: 25%;">Método Detallado</th>
                <th style="width: 20%;" class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">01</td>
                <td>
                    <strong>{{ $pago->concepto ?? 'Pago Académico' }}</strong>
                    @if($pago->numero_modulo)
                        <br><span style="font-size: 9.5px; color: #64748b;">Módulo correspondiente: N° {{ $pago->numero_modulo }}</span>
                    @endif
                </td>
                <td>
                    {{-- Lógica de desglose según forma de pago --}}
                    @if($efectivo > 0 && $operacion > 0)
                        <div>• Efectivo: S/ {{ number_format($efectivo, 2) }}</div>
                        <div>• Dep./Digital: S/ {{ number_format($operacion, 2) }}</div>
                    @elseif($efectivo > 0)
                        <div>• Efectivo directo</div>
                    @else
                        <div>• {{ $pago->formaPago->nombre ?? 'Transf. / Billetera Digital' }}</div>
                        @if($pago->numero_operacion)
                            <div style="font-size: 9px; color: #64748b;">Ref: {{ $pago->numero_operacion }}</div>
                        @endif
                    @endif
                </td>
                <td class="text-right font-mono" style="font-weight: bold;">
                    S/ {{ number_format($totalGeneral, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- TABLA DE TOTALES -->
    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="font-mono">S/ {{ number_format($totalGeneral, 2) }}</td>
        </tr>
        <tr>
            <td>Descuento:</td>
            <td class="font-mono">S/ 0.00</td>
        </tr>
        <tr class="row-total">
            <td>Total Cancelado:</td>
            <td class="font-mono">S/ {{ number_format($totalGeneral, 2) }}</td>
        </tr>
    </table>

    <!-- FIRMA -->
    <div class="footer-container">
        <div class="signature-box">
            <strong>Oficina de Tesorería / Caja</strong><br>
            <span>{{ $institucion->nombre ?? 'Administración Académica' }}</span>
        </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <div class="footer-text">
        Documento de constancia emitido electrónicamente el {{ $fecha_emision }}. Validez académica interna.
    </div>

</body>
</html>