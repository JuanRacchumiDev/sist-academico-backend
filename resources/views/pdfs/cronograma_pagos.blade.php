<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Estado de Cuenta y Cronograma de Pagos</title>
    <style>
        @page { margin: 40px 35px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            font-size: 11px; 
            line-height: 1.4;
        }
        
        /* Cabecera Profesional */
        .header-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        .header-table td { 
            vertical-align: middle; 
        }
        .logo-container {
            width: 80px;
        }
        .logo-placeholder {
            width: 70px;
            height: 70px;
            background-color: #4f46e5;
            color: #ffffff;
            border-radius: 8px;
            text-align: center;
            line-height: 70px;
            font-weight: bold;
            font-size: 24px;
        }
        .logo-img {
            max-width: 75px;
            max-height: 75px;
            border-radius: 4px;
        }
        .inst-info {
            padding-left: 15px;
        }
        .inst-title { 
            font-size: 16px; 
            font-weight: bold; 
            color: #0f172a; 
            text-transform: uppercase; 
            margin: 0;
        }
        .inst-slogan { 
            font-size: 10px; 
            color: #64748b; 
            font-style: italic;
            margin: 2px 0 5px 0;
        }
        .inst-contact {
            font-size: 9px;
            color: #475569;
        }

        .doc-meta { 
            text-align: right; 
            vertical-align: top !important;
        }
        .doc-badge {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-block;
        }
        .doc-id { 
            font-size: 12px; 
            font-weight: bold; 
            color: #4f46e5; 
            margin: 0;
        }
        .doc-date {
            font-size: 9px;
            color: #64748b;
            margin-top: 3px;
        }

        /* Bloques Informativos de Dos Columnas */
        .info-container {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-column {
            width: 49%;
            vertical-align: top;
        }
        .info-card { 
            background: #f8fafc; 
            border: 1px solid #e2e8f0; 
            padding: 12px; 
            border-radius: 6px; 
            height: 115px; /* Altura uniforme */
        }
        .info-card-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4f46e5;
            margin-top: 0;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 2px 0;
            font-size: 10.5px;
        }
        .label { 
            font-weight: bold; 
            color: #475569; 
            width: 30%;
        }
        
        /* Tabla de Cronograma */
        .section-title { 
            color: #0f172a; 
            border-bottom: 2px solid #cbd5e1; 
            padding-bottom: 4px; 
            text-transform: uppercase; 
            font-size: 11px; 
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
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
            font-size: 9px; 
            padding: 8px 6px; 
            text-align: left; 
            letter-spacing: 0.5px;
        }
        .table-data td { 
            padding: 8px 6px; 
            border-bottom: 1px solid #e2e8f0; 
            font-size: 10px; 
            vertical-align: middle; 
        }
        .table-data tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        /* Estados */
        .badge {
            font-weight: bold; 
            padding: 3px 6px; 
            border-radius: 4px; 
            font-size: 8px; 
            display: inline-block;
            text-align: center;
        }
        .status-pagado { 
            background-color: #d1fae5; 
            color: #065f46; 
        }
        .status-pendiente { 
            background-color: #fee2e2; 
            color: #991b1b; 
        }
        
        .footer { 
            position: fixed; 
            bottom: -15px; 
            left: 0; 
            right: 0; 
            text-align: center; 
            font-size: 9px; 
            color: #94a3b8; 
            border-top: 1px solid #e2e8f0; 
            padding-top: 5px; 
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-container">
                @if($institucion['logo'])
                    <img class="logo-img" src="{{ $institucion['logo'] }}" alt="Logo">
                @else
                    <div class="logo-placeholder">
                        {{ substr($institucion['nombre'], 0, 1) }}
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
                    <h2 class="doc-id">ESTADO DE CUENTA</h2>
                    <div class="doc-date">Matrícula: <strong>#{{ str_pad($matricula->id, 6, '0', STR_PAD_LEFT) }}</strong></div>
                </div>
            </td>
        </tr>
    </table>

    <table class="info-container">
        <tr>
            <td class="info-column">
                <div class="info-card">
                    <h3 class="info-card-title">Información del Estudiante</h3>
                    <table class="info-grid">
                        <tr>
                            <td class="label">Estudiante:</td>
                            <td>{{ mb_strtoupper($matricula->persona->nombre_completo ?? 'N/A', 'UTF-8') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Documento:</td>
                            <td>{{ $matricula->persona->numero_documento ?? 'N/A' }}</td>
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
            <td class="info-column">
                <div class="info-card">
                    <h3 class="info-card-title">Detalles Académicos</h3>
                    <table class="info-grid">
                        <tr>
                            <td class="label">F. Matrícula:</td>
                            <td>{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">F. Impresión:</td>
                            <td>{{ $fecha_emision }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="vertical-align: top;">Programas:</td>
                            <td style="font-size: 9.5px; line-height: 1.2;">
                                @foreach($matricula->detalles as $det)
                                    • {{ mb_strtoupper($det->programa?->titulo ?? 'Programa', 'UTF-8') }} 
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
                <th style="width: 15%;">Vencimiento</th>
                <th style="width: 13%;">Estado</th>
                <th style="width: 15%;">F. Pago</th>
                <th style="width: 17%;">Forma de Pago</th>
                <th style="width: 15%;">N° Operación</th>
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
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema académico. <strong>{{ $institucion['nombre'] }}</strong> © {{ date('Y') }}
    </div>

</body>
</html>