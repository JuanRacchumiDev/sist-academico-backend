<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Estado de Cuenta y Cronograma de Pagos</title>
    <style>
        @page { 
            margin: 35px 30px; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            font-size: 10px; 
            line-height: 1.4;
        }
        
        /* Header Struct */
        .header-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
        }
        .header-table td { 
            vertical-align: middle; 
        }
        .logo-container {
            width: 130px;
        }
        .logo-img {
            max-width: 120px;
            max-height: 60px;
            object-fit: contain;
        }
        .logo-placeholder {
            width: 100px;
            height: 45px;
            background-color: #0f172a;
            color: #ffffff;
            border-radius: 4px;
            text-align: center;
            line-height: 45px;
            font-weight: bold;
            font-size: 14px;
        }
        .inst-info {
            padding-left: 10px;
        }
        .inst-title { 
            font-size: 15px; 
            font-weight: 800; 
            color: #0f172a; 
            text-transform: uppercase; 
            margin: 0;
            letter-spacing: 0.5px;
        }
        .inst-slogan { 
            font-size: 9px; 
            color: #64748b; 
            margin: 2px 0 4px 0;
        }
        .inst-contact {
            font-size: 8.5px;
            color: #475569;
        }

        .doc-meta { 
            text-align: right; 
            vertical-align: top !important;
        }
        .doc-badge {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-block;
            text-align: right;
        }
        .doc-id { 
            font-size: 11px; 
            font-weight: bold; 
            color: #2563eb; 
            margin: 0;
            text-transform: uppercase;
        }
        .doc-date {
            font-size: 9px;
            color: #475569;
            margin-top: 3px;
        }

        /* Two Column Grid */
        .info-container {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-column {
            width: 49%;
            vertical-align: top;
        }
        .info-card { 
            background: #f8fafc; 
            border: 1px solid #e2e8f0; 
            padding: 10px 12px; 
            border-radius: 6px; 
            min-height: 105px;
        }
        .info-card-title {
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e293b;
            margin-top: 0;
            margin-bottom: 6px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            letter-spacing: 0.3px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 3px 0;
            font-size: 9.5px;
            vertical-align: top;
        }
        
        /* MEJORA EN LA CLASE LABEL: Ancho controlado y espacio uniforme */
        .label { 
            font-weight: 600; 
            color: #64748b;
            width: 80px; /* Ancho fijo para alineación vertical perfecta */
            white-space: nowrap;
            padding-right: 10px; /* Separación garantizada con la siguiente celda */
        }
        
        /* Table Content */
        .section-title { 
            color: #0f172a; 
            border-bottom: 1px solid #e2e8f0; 
            padding-bottom: 4px; 
            text-transform: uppercase; 
            font-size: 10px; 
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
        }
        .table-data th { 
            background-color: #0f172a; 
            color: #ffffff; 
            text-transform: uppercase; 
            font-size: 8.5px; 
            padding: 7px 6px; 
            text-align: left; 
            letter-spacing: 0.5px;
        }
        .table-data td { 
            padding: 7px 6px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 9.5px; 
            vertical-align: middle; 
        }
        .table-data tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .table-data tfoot td {
            background-color: #f1f5f9;
            font-weight: bold;
            border-top: 1px solid #cbd5e1;
        }
        
        /* Badges */
        .badge {
            font-weight: bold; 
            padding: 2px 6px; 
            border-radius: 4px; 
            font-size: 8px; 
            display: inline-block;
            text-align: center;
        }
        .status-pagado { 
            background-color: #d1fae5; 
            color: #047857; 
        }
        .status-pendiente { 
            background-color: #ffe4e6; 
            color: #be123c; 
        }
        
        .footer { 
            position: fixed; 
            bottom: -10px; 
            left: 0; 
            right: 0; 
            text-align: center; 
            font-size: 8.5px; 
            color: #94a3b8; 
            border-top: 1px solid #f1f5f9; 
            padding-top: 6px; 
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-container">
                @if(!empty($institucion['logo']))
                    <img class="logo-img" src="{{ $institucion['logo'] }}" alt="Logo Institución">
                @else
                    <div class="logo-placeholder">
                        INNOVA
                    </div>
                @endif
            </td>
            <td class="inst-info">
                <h1 class="inst-title">{{ mb_strtoupper($institucion['nombre'], 'UTF-8') }}</h1>
                <p class="inst-slogan">{{ $institucion['sigla'] }}</p>
                <p class="inst-contact">
                    Telf: {{ $institucion['telefono'] }} &nbsp;|&nbsp; Correo: {{ $institucion['email'] }}
                </p>
            </td>
            <td class="doc-meta">
                <div class="doc-badge">
                    <h2 class="doc-id">Estado de Cuenta</h2>
                    <div class="doc-date">Matrícula: <strong>#{{ str_pad($matricula->id, 4, '0', STR_PAD_LEFT) }}</strong></div>
                </div>
            </td>
        </tr>
    </table>

    <table class="info-container">
        <tr>
            <!-- INFORMACIÓN DEL ESTUDIANTE -->
            <td class="info-column">
                <div class="info-card">
                    <h3 class="info-card-title">Información del Estudiante</h3>
                    <table class="info-grid">
                        <tr>
                            <td class="label">Estudiante:</td>
                            <td><strong>{{ mb_strtoupper($matricula->persona->nombre_completo ?? 'N/A', 'UTF-8') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label">Documento:</td>
                            <td><strong>{{ $matricula->persona->numero_documento ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label">Correo:</td>
                            <td>{{ $matricula->persona->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Teléfono:</td>
                            <td>{{ $matricula->persona->telefono ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
            
            <td style="width: 2%;"></td>
            
            <!-- DETALLES ACADÉMICOS -->
            <td class="info-column">
                <div class="info-card">
                    <h3 class="info-card-title">Detalles Académicos</h3>
                    <table class="info-grid">
                        <tr>
                            <td class="label">F. Matrícula:</td>
                            <td><strong>{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label">F. Impresión:</td>
                            <td><strong>{{ $fecha_emision }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label">Programas:</td>
                            <td style="font-size: 8.5px; line-height: 1.3;">
                                @foreach($matricula->detalles as $det)
                                    <div style="margin-bottom: 2px;"><strong>{{ mb_strtoupper($det->programa?->titulo ?? 'Programa', 'UTF-8') }}</strong></div>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <h3 class="section-title">Cronograma y Amortizaciones</h3>
    
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 10%;">Módulo</th>
                <th style="width: 14%;">Vencimiento</th>
                <th style="width: 12%;">Estado</th>
                <th style="width: 14%;">F. Pago</th>
                <th style="width: 18%;">Forma de Pago</th>
                <th style="width: 17%;">N° Operación</th>
                <th style="width: 15%; text-align: right;">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cronograma as $item)
                <tr>
                    <td style="font-weight: bold; color: #0f172a;">Mód. {{ $item['numero_modulo'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['fecha_vencimiento'])->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $item['estado'] === 'PAGADO' ? 'status-pagado' : 'status-pendiente' }}">
                            {{ $item['estado'] }}
                        </span>
                    </td>
                    <td>{{ $item['fecha_pago'] !== '---' ? \Carbon\Carbon::parse($item['fecha_pago'])->format('d/m/Y') : '---' }}</td>
                    <td>{{ mb_strtoupper($item['forma_pago'], 'UTF-8') }}</td>
                    <td style="font-family: monospace;">{{ $item['referencia'] }}</td>
                    <td style="text-align: right; font-weight: bold; color: #0f172a;">
                        S/. {{ number_format($item['monto'], 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;">TOTAL ABONADO:</td>
                <td style="text-align: right; color: #047857;">S/. {{ number_format($montoTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Documento oficial generado automáticamente por el sistema académico. <strong>{{ $institucion['nombre'] }}</strong> © {{ date('Y') }}
    </div>

</body>
</html>