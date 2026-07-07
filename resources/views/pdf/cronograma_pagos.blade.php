<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta y Cronograma de Pagos</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #334155; font-size: 12px; margin: 0; padding: 10px; }
        .header { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .logo-title { font-size: 18px; font-weight: bold; color: #1e1b4b; text-transform: uppercase; }
        .subtitle { font-size: 10px; color: #64748b; margin-top: 4px; }
        .doc-id { text-align: right; font-size: 14px; font-weight: bold; color: #4f46e5; }
        
        .card-info { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 25px; width: 100%; }
        .card-info td { padding: 4px 8px; vertical-align: top; }
        .label { font-weight: bold; color: #475569; font-size: 11px; text-transform: uppercase; }
        
        /* Estilos mejorados para la tabla */
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        .table-data th { background-color: #1e293b; color: #ffffff; text-transform: uppercase; font-size: 10px; padding: 10px 8px; text-align: left; }
        .table-data td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; vertical-align: middle; }
        
        .status-pagado { background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 3px 8px; border-radius: 4px; font-size: 9px; display: inline-block; }
        .status-pendiente { background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 3px 8px; border-radius: 4px; font-size: 9px; display: inline-block; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 5px; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td>
                <div class="logo-title">{{ $matricula->institucion?->nombre ?? 'INSTITUCIÓN ACADÉMICA' }}</div>
                <div class="subtitle">Reporte de Control Financiero y Académico</div>
            </td>
            <td class="doc-id">
                ESTADO DE CUENTA MATRÍCULA #{{ str_pad($matricula->id, 4, '0', STR_PAD_LEFT) }}
            </td>
        </tr>
    </table>

    <div class="card-info">
        <table style="width: 100%;">
            <tr>
                <td class="label" style="width: 18%;">Estudiante:</td>
                <td style="width: 42%;">{{ $matricula->persona->nombre_completo ?? 'N/A' }}</td>
                <td class="label" style="width: 18%;">Fecha Matrícula:</td>
                <td style="width: 22%;">{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Programas:</td>
                <td>
                    @foreach($matricula->detalles as $det)
                        •   {{ $det->programa?->titulo ?? 'Programa' }} 
                            ({{ $det->programa?->tipo_programa?->nombre ?? 'N/A' }})<br>
                    @endforeach
                </td>
                <td class="label">Módulos Totales:</td>
                <td>{{ $matricula->numero_modulos }} Módulos</td>
            </tr>
            <tr>
                <td class="label">Estado Matrícula:</td>
                <td>{{ $matricula->estado_matricula->nombre ?? 'Activo' }}</td>
                <td class="label">Fecha Impresión:</td>
                <td>{{ $fecha_emision }}</td>
            </tr>
        </table>
    </div>

    <h3 style="color: #1e293b; border-bottom: 2px solid #4f46e5; padding-bottom: 5px; text-transform: uppercase; font-size: 12px;">Cronograma y Amortizaciones</h3>
    
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 12%;">N° Módulo</th>
                <th style="width: 14%;">Vencimiento</th>
                <th style="width: 11%;">Estado</th>
                <th style="width: 14%;">F. Cancelación</th>
                <th style="width: 16%;">Medio de Pago</th>
                <th style="width: 18%;">N° Operación / Ref</th>
                <th style="width: 15%; text-align: right;">Monto Abonado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cronograma as $item)
                <tr>
                    <td style="width: 12%; font-weight: bold; color: #1e1b4b;">Mód. {{ $item['numero_modulo'] }}</td>
                    <td style="width: 14%;">{{ \Carbon\Carbon::parse($item['fecha_vencimiento'])->format('d/m/Y') }}</td>
                    <td style="width: 11%;">
                        <span class="{{ $item['estado'] === 'PAGADO' ? 'status-pagado' : 'status-pendiente' }}">
                            {{ $item['estado'] }}
                        </span>
                    </td>
                    <td style="width: 14%;">{{ $item['fecha_pago'] !== '---' ? \Carbon\Carbon::parse($item['fecha_pago'])->format('d/m/Y') : '---' }}</td>
                    <td style="width: 16%;">{{ $item['forma_pago'] }}</td>
                    <td style="width: 18%; font-family: monospace; word-break: break-all;">{{ $item['referencia'] }}</td>
                    <td style="width: 15%; text-align: right; font-weight: bold; color: #0f172a;">
                        S/. {{ number_format($item['monto'], 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Este documento es una proyección oficial de pagos del sistema académico. Sistema IPEDE © {{ date('Y') }}
    </div>

</body>
</html>