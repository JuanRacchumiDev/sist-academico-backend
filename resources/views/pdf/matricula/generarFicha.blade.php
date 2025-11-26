<!DOCTYPE html>
<html>
<head>
    <title>Certificado de Matrícula - {{ $matricula->alumno->nombre_completo ?? 'Alumno' }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; }
        .header { text-align: center; padding: 20px 0; border-bottom: 3px solid #0056b3; margin-bottom: 20px; }
        .header img { float: left; width: 80px; height: 80px; margin-right: 20px; }
        .header h1 { margin: 0; color: #0056b3; font-size: 24px; text-align: center; }
        .header p { margin: 0; font-size: 16px; color: #555; }
        .content { margin: 0 40px; }
        .section-title { font-size: 18px; color: #0056b3; border-bottom: 2px solid #ccc; padding-bottom: 5px; margin-top: 25px; margin-bottom: 15px; }
        .data-row { margin-bottom: 10px; }
        .data-row strong { display: inline-block; width: 150px; color: #333; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo_institucional') }}" alt="Logo institucional" />
        <h1>CERTIFICADO DE MATRÍCULA</h1>
        <p>Documento Oficial del Proceso de Inscripción</p>
    </div>
    <div class="content">
        <div class="section-title">Datos del Alumno</div>
        <div class="data-row">
            <strong>Nombre completo: </strong> {{ $matricula->alumno->nombre_completo ?? 'N/A' }}
        </div>
        <div class="data-row">
            <strong>DNI/Identificación: </strong> {{ $matricula->alumno->numero_documento }}
        </div>
    </div>
</body>
</html>