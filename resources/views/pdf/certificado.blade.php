<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; } /* Sin márgenes para que el fondo cubra todo */
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', sans-serif;
            width: 100%;
            height: 100%;
            background-image: url("{{ $fondo }}");
            background-size: cover;
            background-repeat: no-repeat;
        }

        /* Contenedor relativo para posicionar elementos */
        .content {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Estilo para el nombre del Alumno */
        .alumno-name {
            position: absolute;
            top: 45%; /* Ajustar según tu diseño */
            width: 100%;
            text-align: center;
            font-size: 35px;
            font-weight: bold;
            color: #1a365d;
        }

        /* Estilo para el nombre del Evento */
        .evento-name {
            position: absolute;
            top: 58%;
            width: 80%;
            left: 10%;
            text-align: center;
            font-size: 22px;
            color: #2d3748;
        }

        /* Fechas */
        .fechas {
            position: absolute;
            top: 75%;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #4a5568;
        }

        /* QR de Validación */
        .qr-code {
            position: absolute;
            bottom: 50px;
            right: 50px;
        }

        .qr-text {
            font-size: 8px;
            color: #718096;
            margin-top: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="alumno-name">{{ $certificado->persona->nombres }} {{ $certificado->persona->apellido_paterno }} {{ $certificado->persona->apellido_materno }}</div>
        
        <div class="evento-name">
            Por haber participado y aprobado satisfactoriamente el programa de capacitación en:<br>
            <strong>{{ $programa }}</strong>
        </div>

        <div class="fechas">
            Realizado del {{ $fecha_inicio }} al {{ $fecha_fin }}
        </div>

        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="90">
            <div class="qr-text">VALIDAR CERTIFICADO</div>
        </div>
    </div>
</body>
</html>