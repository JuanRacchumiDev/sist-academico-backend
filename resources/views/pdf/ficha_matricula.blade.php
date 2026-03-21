<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm 1.2cm; }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #1a202c;
            font-size: 10px;
            margin: 0;
            line-height: 1.4;
        }

        .text-primary { color: {{ $institucion->color_primario ?? '#1a365d' }}; }
        .border-primary { border-color: {{ $institucion->color_primario ?? '#1a365d' }}; }
        
        .logo-container { 
            width: 130px; /* Tamaño aumentado para el logo */
            vertical-align: middle; 
        }

        .logo-img {
            max-width: 150px;
            max-height: 130px;
            display: block;
        }

        .inst-details { padding-left: 15px; vertical-align: middle; }
        .inst-details h1 { 
            margin: 0; 
            font-size: 17px; 
            text-transform: uppercase; 
            color: {{ $institucion->color_primario ?? '#1a365d' }};
            letter-spacing: 0.5px;
        }
        .inst-details p { margin: 2px 0; font-size: 8.5px; color: #4a5568; }

        .doc-card {
            width: 200px;
            border: 2px solid {{ $institucion->color_primario ?? '#1a365d' }};
            border-radius: 6px;
            text-align: center;
            padding: 12px;
            background-color: #f8fafc;
        }

        .section-header {
            background-color: #f1f5f9;
            border-left: 5px solid {{ $institucion->color_primario ?? '#1a365d' }};
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            margin: 15px 0 8px 0;
        }

        /* Layout */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .header-table td { vertical-align: middle; }
        
        .inst-info h1 { margin: 0; font-size: 18px; text-transform: uppercase; color: {{ $institucion->color_primario ?? '#1a365d' }}; }
        .inst-info p { margin: 2px 0; font-size: 9px; color: #4a5568; }

        .doc-box { 
            border: 1.5px solid #e2e8f0; 
            border-radius: 6px; 
            text-align: center; 
            padding: 10px;
            background-color: #fcfcfc;
        }

        .section-title {
            background-color: #f7fafc;
            border-left: 4px solid {{ $institucion->color_primario ?? '#1a365d' }};
            padding: 6px 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0 10px 0;
        }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table td { padding: 6px; border-bottom: 1px solid #f1f5f9; }
        .label { font-weight: bold; color: #64748b; width: 22%; }
        
        /* Tabla de cursos */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { 
            background-color: {{ $institucion->color_primario ?? '#1a365d' }}; 
            color: white; 
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }
        .items-table td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; }
        
        .badge {
            /* background-color: #ebf4ff;
            color: #2b6cb0;
            padding: 2px 5px; */
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }

        .footer-table { width: 100%; margin-top: 60px; }
        .signature-line { border-top: 1px solid #2d3748; width: 200px; margin: 0 auto; padding-top: 5px; }

        .signature-box { 
            border-top: 1px solid #1a202c; 
            width: 220px; 
            margin: 0 auto; 
            padding-top: 6px; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo-container">
                @if($logo)
                    <img src="{{ $logo }}" class="logo-img">
                @else
                    <div style="width: 100px; height: 50px; background: #eee; text-align: center; line-height: 50px;">LOGO</div>
                @endif
            </td>
            <td lass="inst-details">
                <h1>{{ $institucion->nombre }}</h1>
                <p><strong>RUC:</strong> {{ $institucion->ruc }}</p>
                <p>{{ $institucion->ubicacion }}</p>
                <p><strong>Contacto:</strong> {{ $institucion->telefono_contacto }} | <strong>Web:</strong> {{ strtolower($institucion->sigla) }}.edu.pe</p>
            </td>
            <td align="right">
                <div class="doc-card">
                    <div class="text-primary" style="font-weight: bold; font-size: 11px; letter-spacing: 1px;">CONSTANCIA DE MATRÍCULA</div>
                    <div style="font-size: 16px; margin: 6px 0; font-weight: bold;">N° {{ $numero_registro }}</div>
                    <div style="font-size: 8px; color: #94a3b8;">Fecha: {{ $fecha }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-header">Información del alumno</div>
    <table class="data-table">
        <tr>
            <td class="label">Apellidos y nombres:</td>
            <td colspan="3" style="font-size: 11px; font-weight: bold;">
                {{ $matricula->persona->apellido_paterno }} {{ $matricula->persona->apellido_materno }}, {{ $matricula->persona->nombres }}
            </td>
        </tr>
        <tr>
            <td class="label">Documento Identidad:</td>
            <td width="30%">{{ $matricula->persona->numero_documento }}</td>
            <td class="label">Fecha Matrícula:</td>
            <td>{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Estado de matrícula:</td>
            <td><strong class="text-primary">{{ $matricula->estadoMatricula->nombre ?? 'PROCESADO' }}</strong></td>
            <td class="label">Periodo Académico:</td>
            <td>{{ $periodo }}</td>
        </tr>
    </table>

    <div class="section-header">Programas y cursos inscritos</div>
    <table class="items-table">
        <thead>
            <tr>
                <th width="12%">Código</th>
                <th width="50%">Programa Académica</th>
                <th width="23%">Tipo de programa</th>
                <th width="15%" style="text-align: center;">Modalidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matricula->detalles as $detalle)
            <tr>
                <td style="font-weight: bold;">{{ $detalle->programa->sigla ?? 'ESP-'.$detalle->id_programa }}</td>
                <td>
                    <div style="font-weight: bold; font-size: 10.5px;">{{ $detalle->programa->titulo }}</div>
                    <div style="font-size: 8px; color: #64748b; margin-top: 2px;">
                        Carga Horaria: {{ $detalle->programa->horas_academicas ?? '0' }} hrs. | Créditos: {{ $detalle->programa->creditos }}
                    </div>
                </td>
                <td>
                    <span class="badge">{{ $detalle->programa->tipoPrograma->nombre ?? 'GENERAL' }}</span>
                </td>
                <td style="text-align: center; font-weight: bold;">{{ $detalle->programa->modalidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td width="50%" style="vertical-align: bottom;">
                <div class="signature-box">
                    <span style="font-size: 10px; font-weight: bold;">Firma del Estudiante</span><br>
                    <span style="font-size: 8px; color: #64748b;">DNI: {{ $matricula->persona->numero_documento }}</span>
                </div>
            </td>
            <td width="50%" align="right">
                <div style="display: inline-block; text-align: center;">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="100">
                    <div style="font-size: 7px; color: #94a3b8; margin-top: 4px; text-transform: uppercase;">Validación Digital Institucional</div>
                </div>
            </td>
        </tr>
    </table>

    <div style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 8px;">
        Esta ficha representa un compromiso académico entre el alumno y {{ $institucion->nombre }}. <br>
        Generado por SGA - Sistema de Gestión Académica © {{ now()->year }}
    </div>
</body>
</html>