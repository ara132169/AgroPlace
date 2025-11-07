<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje de contacto</title>
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
        .message-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #1254A1;
        }
        .sender-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .sender-info h3 {
            margin: 0 0 10px 0;
            color: #1254A1;
        }
        .technical-info {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            font-size: 12px;
            color: #666;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .highlight {
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Nuevo Mensaje de Contacto</h1>
        </div>
        
        <div class="content">
            <div class="highlight">
                <strong>🔔 Has recibido un nuevo mensaje de contacto desde tu sitio web AgroPlace</strong>
            </div>
            
            <div class="sender-info">
                <h3>📋 Información del remitente:</h3>
                <p><strong>Nombre:</strong> {{ $nombre_remitente }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $email_remitente }}">{{ $email_remitente }}</a></p>
                <p><strong>Fecha:</strong> {{ $fecha }}</p>
            </div>
            
            <div class="message-info">
                <h3>💬 Mensaje:</h3>
                <div style="background: white; padding: 15px; border-radius: 5px; white-space: pre-wrap;">{{ $mensaje }}</div>
            </div>
            
            <div class="highlight">
                <p><strong>💡 ¿Cómo responder?</strong></p>
                <p>Puedes responder directamente a este email haciendo clic en "Responder" en tu cliente de correo. El mensaje se enviará automáticamente a <strong>{{ $email_remitente }}</strong></p>
            </div>
            
            <div class="technical-info">
                <h4>ℹ️ Información técnica:</h4>
                <p><strong>IP:</strong> {{ $ip_address }}</p>
                <p><strong>Navegador:</strong> {{ $user_agent }}</p>
            </div>
        </div>
        
        <div class="footer">
            <p>Este email fue generado automáticamente desde el formulario de contacto de AgroPlace<br>
            <strong>No responder a este email directamente</strong> - Utiliza el botón "Responder" para contactar al remitente.</p>
        </div>
    </div>
</body>
</html>