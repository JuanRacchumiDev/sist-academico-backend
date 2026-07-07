<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Matrícula</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; color: #333333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #1e3a8a; padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 30px; }
        .welcome { font-size: 18px; font-weight: bold; color: #1e3a8a; margin-bottom: 10px; }
        .credentials-box { background-color: #f0f4f8; border-left: 4px solid #3b82f6; padding: 20px; margin: 20px 0; border-radius: 0 4px 4px 0; }
        .credentials-box p { margin: 5px 0; font-size: 15px; }
        .table-title { font-size: 16px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; color: #1e3a8a; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; }
        .table-detail { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-detail th { background-color: #f8fafc; text-align: left; padding: 10px; font-size: 14px; color: #64748b; border-bottom: 1px solid #e5e7eb; }
        .table-detail td { padding: 12px 10px; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
        .btn { display: inline-block; background-color: #3b82f6; color: #ffffff !important; text-decoration: none; padding: 12px 25px; font-weight: bold; border-radius: 5px; margin-top: 15px; text-align: center; }
        .footer { background-color: #f8fafc; text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>SISTEMA ACADÉMICO</h1>
        </div>

        <div class="content">
            <p class="welcome">¡Hola, {{ $matricula->persona->nombre_completo }}!</p>
            <p>Te damos una cordial bienvenida a nuestra institución. Tu proceso de matrícula ha sido procesado de manera exitosa. A continuación, te compartimos tus credenciales de acceso a la plataforma estudiantil:</p>

            <div class="credentials-box">
                <p><strong>URL de Acceso:</strong> <a href="{{ config('app.url') }}" style="color: #3b82f6;">Portal de Alumnos</a></p>
                <p><strong>Usuario (Email):</strong> {{ $matricula->persona->correo ?? 'Tu correo registrado' }}</p>
                <p><strong>Contraseña Temporal:</strong> <code>{{ $passwordTemporal }}</code></p>
            </div>
            <small style="color: #64748b;">* Por seguridad, te recomendamos cambiar tu contraseña una vez ingreses por primera vez.</small>

            <div class="table-title">Resumen de Matrícula (Ref: N° {{ sprintf('%05d', $matricula->id) }})</div>
            <table class="table-detail">
                <thead>
                    <tr>
                        <th>Programa académico</th>
                        <th style="text-align: right;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matricula->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->programa->titulo ?? 'Programa Académico' }}</td>
                            <td style="text-align: right;"><span style="color: #10b981; font-weight: bold;">Activo</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p style="margin-top: 20px;">Ante cualquier duda o consulta técnica, puedes responder de forma directa a este correo o comunicarte con el área de soporte académico.</p>
            
            <center>
                <a href="{{ config('app.url') }}" class="btn">Ingresar al Aula Virtual</a>
            </center>
        </div>

        <div class="footer">
            Este es un correo automático, por favor no lo responda.<br>
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </div>
    </div>

</body>
</html>