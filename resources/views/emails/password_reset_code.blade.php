<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f5f7fa;
            color: #2c3e50;
            padding: 20px;
        }

        .container {
            max-width: 560px;
            width: 100%;
            margin: auto;
            background: #ffffff;
            padding: 30px 35px;
            border-radius: 12px;
            border: 1px solid #e6e9ed;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        h2 {
            text-align: center;
            color: #1a73e8;
            margin-bottom: 15px;
            font-weight: 600;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
        }

        .code {
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 8px;
            margin: 30px 0;
            padding: 20px;
            background: #eef4ff;
            border-radius: 10px;
            color: #1a73e8;
            border: 1px solid #d5e3ff;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #7f8c8d;
        }
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
            Gracias,<br>El equipo de <strong>Soccer Archive</strong>
        </p>
    </div>
</body>
</html>
