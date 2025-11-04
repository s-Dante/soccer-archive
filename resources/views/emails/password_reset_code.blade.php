<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .code { font-size: 32px; font-weight: bold; text-align: center; letter-spacing: 5px; margin: 25px 0; padding: 15px; background-color: #f4f4f4; border-radius: 5px; }
        .footer { margin-top: 20px; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Restablecimiento de Contraseña</h2>
        <p>Hola,</p>
        <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Usa el siguiente código para continuar:</p>
        
        <div class="code">
            {{ $code }}
        </div>
        
        <p>Este código expirará en 10 minutos. Si no solicitaste esto, puedes ignorar este correo de forma segura.</p>
        
        <p class="footer">
            Gracias,<br>El equipo de Soccer Archive
        </p>
    </div>
</body>
</html>

