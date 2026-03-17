<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Configuraciones de página */
        @page { margin: 1.5cm; }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #2d3748;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Colores Institucionales */
        .text-primary { color: #1a365d; }
        .bg-primary { background-color: #1a365d; color: white; }
        .border-bottom { border-bottom: 2px solid #1a365d; }

        /* Encabezado Estilo Vertical Profesional */
        .header-table { width: 100%; border-bottom: 3px solid #1a365d; margin-bottom: 20px; padding-bottom: 10px; }
        .logo-container { width: 20%; }
        .institution-details { width: 50%; padding-left: 15px; }
        .doc-info { width: 30%; text-align: right; }
        
        .institution-details h1 { margin: 0; font-size: 18px; color: #1a365d; text-transform: uppercase; }
        .institution-details p { margin: 2px 0; font-size: 9px; color: #718096; }

        /* Bloques de Información */
        .section-header {
            background-color: #edf2f7;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #1a365d;
        }

        .data-table { width: 100%; margin-bottom: 15px; }
        .data-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; color: #4a5568; width: 30%; }
        .value { color: #2d3748; border-bottom: 1px solid #e2e8f0; }

        /* Tabla de Programas Académicos */
        .programs-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .programs-table th {
            background-color: #1a365d;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        .programs-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .programs-table tr:nth-child(even) { background-color: #f8fafc; }

        /* Etiquetas (Badges) para Tipos */
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            background-color: #e2e8f0;
            color: #2d3748;
            font-weight: bold;
        }

        /* Footer y QR */
        .footer { position: fixed; bottom: 0; width: 100%; font-size: 9px; color: #a0aec0; border-top: 1px solid #e2e8f0; padding-top: 5px; }
        .qr-section { position: absolute; bottom: 40px; right: 0; text-align: center; width: 120px; }
        .qr-section img { margin-bottom: 5px; }
        
        .signature-box { margin-top: 50px; width: 200px; border-top: 1px solid #2d3748; text-align: center; padding-top: 5px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-container">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 70px;">
            </td>
            <td class="institution-details">
                <h1>{{ config('app.name', 'INSTITUTO SUPERIOR') }}</h1>
                <p>Centro de Formación Profesional Especializada</p>
                <p>RUC: 20XXXXXXXXX | Tel: (01) XXX-XXXX</p>
                <p>Web: www.institucion.edu.pe</p>
            </td>
            <td class="doc-info">
                <h2 style="margin:0; font-size: 14px; color: #1a365d;">CONSTANCIA DE MATRÍCULA</h2>
                <p style="margin:5px 0; font-weight: bold;">N° Registro: {{ str_pad($matricula->id, 8, '0', STR_PAD_LEFT) }}</p>
                <p style="margin:0; font-size: 9px;">Fecha Emisión: {{ now()->format('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>

    <div class="section-header">Información del Estudiante</div>
    <table class="data-table">
        <tr>
            <td class="label">Apellidos y Nombres:</td>
            <td class="value" colspan="3">{{ $matricula->persona->apellido_paterno }} {{ $matricula->persona->apellido_materno }} {{ $matricula->persona->nombres }}</td>
        </tr>
        <tr>
            <td class="label">Documento Identidad:</td>
            <td class="value">{{ $matricula->persona->numero_documento }}</td>
            <td class="label" style="padding-left: 20px;">Fecha de Matrícula:</td>
            <td class="value">{{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Estado Matrícula:</td>
            <td class="value">{{ $matricula->estadoMatricula->nombre ?? 'REGISTRADO' }}</td>
            <td class="label" style="padding-left: 20px;">Ciclo Académico:</td>
            <td class="value">{{ now()->year }} - II</td>
        </tr>
    </table>

    <div class="section-header">Programas Académicos Inscritos</div>
    <table class="programs-table">
        <thead>
            <tr>
                <th style="width: 10%;">Código</th>
                <th style="width: 45%;">Nombre del Programa</th>
                <th style="width: 25%;">Tipo / Categoría</th>
                <th style="width: 20%; text-align: center;">Modalidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matricula->detalles as $detalle)
            <tr>
                <td>{{ $detalle->programa->sigla ?? 'PROG-'.$detalle->id_programa }}</td>
                <td>
                    <div style="font-weight: bold;">{{ $detalle->programa->titulo }}</div>
                    <div style="font-size: 8px; color: #718096;">Duración: {{ $detalle->programa->duracion }} | Créditos: {{ $detalle->programa->creditos }}</div>
                </td>
                <td>
                    <span class="badge">{{ $detalle->programa->tipoPrograma->nombre ?? 'N/A' }}</span>
                    <br>
                    <small style="color: #718096;">{{ $detalle->programa->categoriaPrograma->nombre ?? '' }}</small>
                </td>
                <td style="text-align: center;">{{ $detalle->programa->modalidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px;">
        <table width="100%">
            <tr>
                <td width="50%">
                    <div class="signature-box">
                        <p style="font-size: 10px; margin: 0;">Firma del Estudiante</p>
                        <p style="font-size: 8px; color: #718096;">DNI: __________________</p>
                    </div>
                </td>
                <td width="50%" align="right">
                    <div style="text-align: center; display: inline-block;">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="90">
                        <p style="font-size: 8px; color: #718096; margin-top: 5px;">Documento Verificado digitalmente</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Este documento es una constancia oficial de matrícula. Cualquier enmendadura invalida su contenido.
        <br>
        Generado por Sistema de Gestión Académica - {{ now()->year }}
    </div>

</body>
</html>