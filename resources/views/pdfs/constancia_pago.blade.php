<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Pago - {{ $pago->id }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            line-height: 1.4;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        td {
            vertical-align: top;
        }
        
        /* Encabezado */
        .header-table {
            margin-bottom: 30px;
        }
        .inst-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a; /* Azul institucional */
            text-transform: uppercase;
            margin: 0;
        }
        .inst-subtitle {
            font-size: 11px;
            color: #64748b;
            font-style: italic;
            margin-top: 3px;
        }
        .inst-details {
            font-size: 11px;
            color: #475569;
            margin-top: 5px;
            line-height: 1.3;
        }
        
        /* Cuadro de Comprobante (RUC / Tipo) */
        .document-box {
            border: 2px solid #1e3a8a;
            border-radius: 8px;
            text-align: center;
            padding: 15px;
            background-color: #f8fafc;
        }
        .document-box .ruc {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1px;
        }
        .document-box .title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            color: #0f172a;
            text-transform: uppercase;
        }
        .document-box .number {
            font-size: 15px;
            font-weight: bold;
            color: #dc2626; /* Rojo para destacar el ID */
        }

        /* Secciones Informativas */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            background-color: #f1f5f9;
            color: #1e3a8a;
            padding: 5px 10px;
            margin-bottom: 10px;
            border-left: 4px solid #1e3a8a;
            text-transform: uppercase;
        }

        .info-table td {
            padding: 4px 0;
            font-size: 12px;
        }
        .info-label {
            width: 25%;
            font-weight: bold;
            color: #475569;
        }
        .info-value {
            width: 75%;
            color: #0f172a;
        }

        /* Tabla de Detalles del Pago */
        .items-table {
            width: 100%;
            margin-top: 15px;
        }
        .items-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 10px;
            font-size: 11px;
            text-align: left;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Totales */
        .totals-table {
            width: 40%;
            float: right;
            margin-top: 10px;
        }
        .totals-table td {
            padding: 6px 10px;
            font-size: 12px;
        }
        .total-row {
            background-color: #ecfdf5; /* Fondo verde suave */
            font-weight: bold;
            color: #065f46;
            border-top: 1px solid #10b981;
        }

        /* Footer / Nota */
        .footer-note {
            margin-top: 150px; /* Margen para empujar hacia abajo y dar espacio a firmas */
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px dashed #cbd5e1;
            padding-top: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        .signature-section {
            margin-top: 60px;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #64748b;
            margin: 0 auto;
            padding-top: 5px;
            font-size: 11px;
            color: #475569;
        }
    </style>
</head>
<body>

    <!-- ENCABEZADO Y CAJA DE COMPROBANTE -->
    <table class="header-table">
        <tr>
            <!-- Datos de la Institución -->
            <td style="width: 55%;">
                <h1 class="inst-title">{{ $institucion->nombre ?? 'INNOVAPERU' }}</h1>
                <div class="inst-subtitle">{{ $institucion->sigla ?? 'Aprendizaje continuo para ti' }}</div>
                <div class="inst-details">
                    <strong>Ubicación:</strong> {{ $institucion->ubicacion ?? 'No especificada' }}<br>
                    @if($pago->matricula->institucion->telefono_contacto)
                        <strong>Teléfono:</strong> {{ $pago->matricula->institucion->telefono_contacto }}<br>
                    @endif
                    <strong>Email:</strong> {{ $institucion->email ?? 'contacto@innovaperu.edu.pe' }}
                </div>
            </td>
            <!-- Cuadro de la Constancia -->
            <td style="width: 45%;">
                <div class="document-box">
                    <div class="ruc">R.U.C. {{ $institucion->ruc ?? '20204040200' }}</div>
                    <div class="title">Constancia de Pago</div>
                    <div class="number">N° {{ str_pad($pago->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATOS DEL ESTUDIANTE -->
    <div class="section-title">Datos del Estudiante</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Estudiante:</td>
            <td class="info-value">{{ $pago->matricula->persona->nombre_completo ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Documento:</td>
            <td class="info-value">{{ $pago->matricula->persona->numero_documento ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Dirección:</td>
            <td class="info-value">{{ $pago->matricula->persona->direccion_completa ?? 'No registrada' }}</td>
        </tr>
    </table>

    <!-- DETALLES DEL COMPROBANTE -->
    <div class="section-title">Detalles de la Transacción</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Fecha de Pago:</td>
            <td class="info-value">
                {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td class="info-label">Forma de Pago:</td>
            <td class="info-value">{{ $pago->formaPago->nombre ?? 'No especificada' }}</td>
        </tr>
        <tr>
            <td class="info-label">N° de Operación:</td>
            <td class="info-value">
                <span style="font-family: monospace; font-size: 13px; font-weight: bold;">
                    {{ $pago->numero_operation ?? $pago->numero_operacion ?? 'Sin número' }}
                </span>
            </td>
        </tr>
    </table>

    <!-- TABLA DE ITEMS -->
    <div class="section-title">Concepto de Cobro</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 10%;" class="text-center">Ítem</th>
                <th style="width: 65%;">Descripción del Concepto / Módulo</th>
                <th style="width: 25%;" class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>
                    <strong>{{ $pago->concepto ?? 'PAGO DE MATRÍCULA / MÓDULO' }}</strong>
                    @if($pago->numero_modulo)
                        <br><span style="font-size: 11px; color: #64748b;">Correspondiente al Módulo N° {{ $pago->numero_modulo }}</span>
                    @endif
                </td>
                <td class="text-right">
                    S/. {{ number_format($pago->cantidad_operacion ?? 0.00, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- TOTALES -->
    <table class="totals-table">
        <tr>
            <td class="text-right" style="width: 50%;">Subtotal:</td>
            <td class="text-right" style="width: 50%;">S/. {{ number_format($pago->cantidad_operacion ?? 0.00, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">Descuentos:</td>
            <td class="text-right">S/. 0.00</td>
        </tr>
        <tr class="total-row">
            <td class="text-right">Total Pagado:</td>
            <td class="text-right">S/. {{ number_format($pago->cantidad_operacion ?? 0.00, 2) }}</td>
        </tr>
    </table>

    <!-- SECCIÓN DE FIRMAS -->
    <table style="width: 100%; margin-top: 80px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <!-- Espacio libre para firma del alumno si se requiere -->
            </td>
            <td style="width: 50%; text-align: center;">
                <div class="signature-section">
                    <div class="signature-line">Oficina de Tesorería</div>
                    <div style="font-size: 10px; color: #64748b;">{{ $institucion->nombre ?? 'INNOVAPERU' }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- PIE DE PÁGINA -->
    <div class="footer-note">
        Esta es una copia informativa de constancia de pago electrónica generada en el sistema académico el {{ $fecha_emision }}.
    </div>

</body>
</html>