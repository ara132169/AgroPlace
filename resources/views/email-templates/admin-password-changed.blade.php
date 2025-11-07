<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña Actualizada - {{ env('APP_NAME') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e9ecef;
        }
        .alert {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 14px;
            color: #6c757d;
        }
        .security-tips {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔐 Contraseña Actualizada</h1>
        <p>{{ env('APP_NAME') }}</p>
    </div>

    <div class="content">
        <h2>Hola, {{ $admin->name }}</h2>
        
        <div class="alert">
            <strong>✅ Contraseña actualizada exitosamente</strong><br>
            Tu contraseña de administrador ha sido cambiada correctamente.
        </div>

        <p>Te informamos que tu contraseña de administrador ha sido actualizada exitosamente en nuestra plataforma.</p>

        <div class="info-box">
            <h3>📋 Detalles del cambio:</h3>
            <ul>
                <li><strong>Usuario:</strong> {{ $admin->name }}</li>
                <li><strong>Email:</strong> {{ $admin->email }}</li>
                <li><strong>Fecha y hora:</strong> {{ $date }}</li>
                <li><strong>Dirección IP:</strong> {{ $ip }}</li>
            </ul>
        </div>

        <div class="security-tips">
            <h3>🛡️ Recomendaciones de seguridad:</h3>
            <ul>
                <li>Mantén tu contraseña segura y no la compartas con nadie</li>
                <li>Usa una contraseña única para tu cuenta de administrador</li>
                <li>Cierra sesión después de usar el panel de administración</li>
                <li>Si no realizaste este cambio, contacta al soporte técnico inmediatamente</li>
            </ul>
        </div>

        <p><strong>⚠️ Importante:</strong> Si no fuiste tú quien realizó este cambio, comunícate inmediatamente con el equipo de soporte técnico.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.login') }}" style="background-color: #2563eb; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block;">
                Acceder al Panel de Administración
            </a>
        </div>
    </div>

    <div class="footer">
        <p>Este es un mensaje automático de seguridad de {{ env('APP_NAME') }}</p>
        <p>© {{ date('Y') }} {{ env('APP_NAME') }}. Todos los derechos reservados.</p>
        <p style="font-size: 12px; color: #adb5bd;">
            Este email fue enviado desde la dirección IP: {{ $ip }}<br>
            Si tienes problemas con este enlace, copia y pega la siguiente URL en tu navegador:<br>
            {{ route('admin.login') }}
        </p>
    </div>
</body>
</html>