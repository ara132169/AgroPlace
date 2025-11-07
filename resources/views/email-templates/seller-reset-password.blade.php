<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - AgroPlace</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1254A1 0%, #0d4085 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #1254A1 0%, #0d4085 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(18, 84, 161, 0.3);
            transition: all 0.3s ease;
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(18, 84, 161, 0.4);
            color: white;
            text-decoration: none;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #1254A1;
        }
        .warning-box {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .link-text {
            word-break: break-all;
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Recuperar Contraseña</h1>
            <p>AgroPlace - Tu marketplace agrícola</p>
        </div>
        
        <div class="content">
            <h2>¡Hola!</h2>
            
            <p>Recibiste este correo porque se solicitó un restablecimiento de contraseña para tu cuenta de vendedor en <strong>AgroPlace</strong>.</p>
            
            <div class="info-box">
                <strong>📧 Correo de la cuenta:</strong> {{ $email }}<br>
                <strong>🕒 Válido por:</strong> 60 minutos<br>
                <strong>📅 Solicitado el:</strong> {{ date('d/m/Y H:i') }}
            </div>
            
            <p>Para restablecer tu contraseña, haz clic en el siguiente botón:</p>
            
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    🔄 Restablecer mi contraseña
                </a>
            </div>
            
            <div class="warning-box">
                <strong>⚠️ Importante:</strong>
                <ul>
                    <li>Este enlace expirará en <strong>60 minutos</strong></li>
                    <li>Solo puedes usar este enlace una vez</li>
                    <li>Si no solicitaste este cambio, ignora este email</li>
                </ul>
            </div>
            
            <p><strong>¿Problemas con el botón?</strong> Copia y pega este enlace en tu navegador:</p>
            <div class="link-text">{{ $resetUrl }}</div>
            
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
            
            <h3>🛡️ Consejos de seguridad:</h3>
            <ul>
                <li>Usa una contraseña segura con al menos 8 caracteres</li>
                <li>Combina letras, números y símbolos</li>
                <li>No compartas tu contraseña con nadie</li>
                <li>Cierra sesión al usar computadoras públicas</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Este email fue generado automáticamente desde <strong>AgroPlace</strong><br>
            Si no solicitaste este cambio, puedes ignorar este mensaje de forma segura.</p>
            
            <p>¿Necesitas ayuda? Contacta a nuestro equipo de soporte:<br>
            📧 <strong>{{ config('mail.from.address', 'soporte@agroplace.com') }}</strong></p>
        </div>
    </div>
</body>
</html>