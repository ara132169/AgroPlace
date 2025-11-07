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
        .message-box {
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
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #1254A1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Nuevo Mensaje de Contacto</h1>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $vendedor_name }}</strong>,</p>
            
            <p>Has recibido un nuevo mensaje de contacto a través de tu perfil de vendedor en AgroMarket.</p>
            
            <div class="sender-info">
                <h3>Información del remitente:</h3>
                <p><strong>Nombre:</strong> {{ $remitente_name }}</p>
                <p><strong>Email:</strong> {{ $remitente_email }}</p>
                <p><strong>Fecha:</strong> {{ $fecha }}</p>
            </div>
            
            <div class="message-box">
                <h3>Mensaje:</h3>
                <p>{{ $mensaje }}</p>
            </div>
            
            <p>Puedes responder directamente a este email para contactar con {{ $remitente_name }}.</p>
            
            <p>¡Gracias por ser parte de nuestra comunidad!</p>
        </div>
        
        <div class="footer">
            <p>Este email fue enviado desde AgroMarket<br>
            Si tienes alguna pregunta, no dudes en contactarnos.</p>
        </div>
    </div>
</body>
</html>