<!DOCTYPE html>
<html>
    <head>
        <title>Bienvenido</title>
    </head>
    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
        <h2>¡Hola, {{ $user->name }}</h2>
        <p>Se ha creado tu cuenta con éxito en nuestra plataforma</p>

        <div style="background-color: #f4f4f4; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>Tus credenciales de acceso:</strong><br>
            <strong>Correo:</strong> {{ $user->email }}<br>
            <strong>Contraseña temporal:</strong> {{ $plainPassword }}
        </div>

        <p>Te recomendamos cambiar tu contraseña una vez que inicies sesión por primera vez.</p>
        <br>
        <p>Saludos,<br>El equipo de soporte.</p>
    </body>
</html>