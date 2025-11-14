<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Tienda - {{ $siteName }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        .welcome-title {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-box {
            background-color: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .status-badge {
            background-color: #ffc107;
            color: #000;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .next-steps {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps h3 {
            color: #495057;
            margin-top: 0;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin: 8px 0;
        }
        .contact-info {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🌱 {{ $siteName }}</div>
            <p>Tu plataforma de comercio agrícola</p>
        </div>

        <h1 class="welcome-title">¡Bienvenido, {{ $sellerName }}!</h1>

        <p>Nos complace confirmar que hemos recibido tu solicitud de registro como tienda en <strong>{{ $siteName }}</strong>.</p>

        <div class="info-box">
            <h3>📋 Detalles de tu registro:</h3>
            <ul>
                <li><strong>Nombre de la tienda:</strong> {{ $sellerName }}</li>
                <li><strong>Correo electrónico:</strong> {{ $sellerEmail }}</li>
                <li><strong>Fecha de registro:</strong> {{ $registrationDate }}</li>
            </ul>
        </div>

        <div class="warning">
            <h3>⏳ Estado actual:</h3>
            <span class="status-badge">En Revisión</span>
            <p>Tu solicitud está siendo revisada por nuestro equipo de administradores. Este proceso puede tomar de <strong>1 a 3 días hábiles</strong>.</p>
        </div>

        <div class="next-steps">
            <h3>📝 Próximos pasos:</h3>
            <ul>
                <li>✅ <strong>Registro recibido</strong> - ¡Ya completaste este paso!</li>
                <li>🔍 <strong>Revisión administrativa</strong> - Nuestro equipo verificará tu información</li>
                <li>📧 <strong>Notificación de aprobación</strong> - Te enviaremos un correo cuando tu cuenta sea aprobada</li>
                <li>🏪 <strong>Configuración de tienda</strong> - Podrás acceder y configurar tu tienda</li>
                <li>🚀 <strong>¡Comienza a vender!</strong> - Publica tus productos y comienza a recibir órdenes</li>
            </ul>
        </div>

        <div class="contact-info">
            <h3>💬 ¿Necesitas ayuda?</h3>
            <p>Si tienes alguna pregunta sobre el proceso de registro o necesitas asistencia, no dudes en contactarnos:</p>
            <ul>
                <li>📧 <strong>Email:</strong> soporte@agroplace.com</li>
                <li>🕒 <strong>Horario de atención:</strong> Lunes a Viernes, 8:00 AM - 6:00 PM</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <p>Mientras tanto, puedes explorar nuestra plataforma:</p>
            <a href="{{ url('/') }}" class="btn">Visitar {{ $siteName }}</a>
        </div>

        <div class="footer">
            <p><strong>{{ $siteName }}</strong> - Conectando productores con consumidores</p>
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>Si no solicitaste registrarte en {{ $siteName }}, puedes ignorar este correo.</p>
            <hr>
            <p style="font-size: 12px; color: #999;">
                © {{ date('Y') }} {{ $siteName }}. Todos los derechos reservados.<br>
                Este correo fue enviado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
            </p>
        </div>
    </div>
</body>
</html>