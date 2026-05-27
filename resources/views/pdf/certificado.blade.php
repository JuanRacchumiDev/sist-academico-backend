<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Estudios</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #fcfbf7;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .cert-container {
            padding: 50px;
            width: 1024px;
            height: 620px;
            position: relative;
        }
        /* Borde Elegante de Doble Línea */
        .cert-border {
            border: 6px double #8a6d3b;
            padding: 40px;
            height: 530px;
            text-align: center;
            background-color: #ffffff;
        }
        .header .logo {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #1a252f;
            text-transform: uppercase;
        }
        .header .subtitle {
            font-size: 11px;
            color: #7f8c8d;
            margin-top: 5px;
            text-transform: uppercase;
        }
        .cert-title {
            font-size: 42px;
            color: #8a6d3b;
            margin-top: 30px;
            margin-bottom: 10px;
            font-family: 'Times New Roman', Times, serif;
            letter-spacing: 2px;
        }
        .presentado-a {
            font-size: 14px;
            font-style: italic;
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        .alumno-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #dfd7ca;
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 25px;
        }
        .body-text {
            font-size: 15px;
            line-height: 1.8;
            margin: 0 auto;
            width: 85%;
            color: #34495e;
        }
        .programa-titulo {
            font-weight: bold;
            color: #111;
        }
        .footer-dates {
            margin-top: 50px;
            font-size: 13px;
            color: #7f8c8d;
        }
        .signatures {
            margin-top: 60px;
        }
        .signature-block {
            float: left;
            width: 40%;
            margin-left: 7%;
            margin-right: 7%;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        .signature-line {
            border-top: 1px solid #95a5a6;
            margin-bottom: 8px;
            padding-top: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="cert-container">
    <div class="cert-border">
        
        <div class="header">
            <div class="logo">INSTITUCIÓN EDUCATIVA SUPERIOR</div>
            <div class="subtitle">Resolución Ministerial N° 2026-ED</div>
        </div>

        <div class="cert-title">CERTIFICADO DE ESTUDIOS</div>
        
        <div class="presentado-a">Otorgado con orgullo a:</div>
        
        <div class="alumno-name">{{ $info->nombre_alumno }}</div>
        
        <div class="body-text">
            Por haber aprobado satisfactoriamente todos los requisitos académicos correspondientes al 
            <span class="unidades">{{ $info->nombre_tipoprograma }}</span> denominado: <br>
            <span class="programa-titulo">"{{ $info->titulo_programa }}"</span><br>
            Desarrollado desde el {{ $info->fecha_inicio_letras }} hasta el {{ $info->fecha_final_letras }}, 
            con una carga lectiva estructurada de un total de <strong>{{ $info->numero_modulos }} módulos</strong> académicos.
        </div>

        <div class="footer-dates">
            Emitido el {{ $info->fecha_emision }}
        </div>

        <div class="signatures">
            <div class="signature-block">
                <div class="signature-line">Dirección Académica</div>
                <div>Sello de la Institución</div>
            </div>
            <div class="signature-block">
                <div class="signature-line">Secretaría General</div>
                <div>Registro Nacional de Certificados</div>
            </div>
        </div>

    </div>
</div>

</body>
</html>