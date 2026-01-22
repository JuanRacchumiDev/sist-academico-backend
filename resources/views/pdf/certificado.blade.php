@php
    use Carbon\Carbon;
    $m = $matricula;
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Times New Roman', serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .border-double {
            margin: 30px;
            padding: 40px;
            border: 10px double #003366;
            height: 900px;
        }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { 
            color: #003366; 
            font-size: 32pt; 
            margin: 0; 
            letter-spacing: 3px;
        }
        .header h2 { font-size: 14pt; color: #666; font-style: italic; }
        
        .content { margin-top: 50px; line-height: 1.8; font-size: 13pt; text-align: justify; }
        .student-name { 
            display: block; 
            text-align: center; 
            font-size: 18pt; 
            font-weight: bold; 
            margin: 20px 0;
            text-decoration: underline;
        }
        .program-box {
            text-align: center;
            background-color: #f9f9f9;
            border: 1px solid #003366;
            padding: 15px;
            margin: 25px auto;
            width: 85%;
            font-weight: bold;
            color: #cc0000;
            font-size: 15pt;
        }
        .details-table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
        }
        .details-table th {
            text-align: left;
            background-color: #eee;
            padding: 8px;
            border: 1px solid #ccc;
            width: 40%;
        }
        .details-table td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        
        .footer { margin-top: 60px; }
        .signature-section {
            float: right;
            width: 300px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }
        .qr-section {
            float: left;
            width: 200px;
            text-align: center;
            font-size: 8pt;
        }
        .clearfix { clear: both; }
    </style>
</head>
<body>
    <div class="border-double">
        <div class="header">
            <img src="{{ public_path('LOGO.jpg') }}" style="width: 80px; margin-bottom: 10px;">
            <h1>{{ $title }}</h1>
            <h2>Instituto Profesional de Educación y Desarrollo Empresarial</h2>
        </div>

        <div class="content">
            <p style="text-align: center;">La Dirección Académica de <strong>IPEDE</strong> hace constar que:</p>
            
            <span class="student-name">{{ $m['nombre_completo'] }}</span>
            
            <p style="text-align: center;">Identificado(a) con DNI N° <strong>{{ $m['numero_documento'] }}</strong>, se encuentra debidamente registrado(a) y matriculado(a) en el programa de capacitación de:</p>

            <div class="program-box">
                "{{ $m['nombre_programa'] }}"
            </div>

            <table class="details-table">
                @if($m['fecha_inicio'])
                <tr>
                    <th>Fecha de Inicio</th>
                    <td>{{ Carbon::parse($m['fecha_inicio'])->format('d/m/Y') }}</td>
                </tr>
                @endif
                @if($m['horas_academicas'])
                <tr>
                    <th>Horas Académicas</th>
                    <td>{{ $m['horas_academicas'] }} Horas Lectivas</td>
                </tr>
                @endif
                @if($m['modalidad'])
                <tr>
                    <th>Modalidad</th>
                    <td>{{ $m['modalidad'] }}</td>
                </tr>
                @endif
            </table>

            <p style="margin-top: 30px;">Se expide la presente a solicitud del interesado, para los fines que estime convenientes, con fecha de registro de matrícula {{ Carbon::parse($m['fecha_matricula'])->format('d/m/Y') }}.</p>
        </div>

        <div class="footer">
            <div class="qr-section">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="90">
                <p>Verificación Digital<br>{{ $validationUrl }}</p>
            </div>
            
            <div class="signature-section">
                <div style="height: 80px;"></div>
                <div class="signature-line"></div>
                <strong>DIRECCIÓN ACADÉMICA</strong><br>
                IPEDE
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</body>
</html>