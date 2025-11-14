<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Cuenta Aprobada! - {{ $siteName }}</title>
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
        .celebration {
            text-align: center;
            font-size: 4rem;
            margin: 20px 0;
        }
        .approved-title {
            color: #28a745;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success-box {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        .info-box {
            background-color: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
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
        .step {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 10px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .step-number {
            background-color: #28a745;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #218838;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .benefits {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
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
        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .feature-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🌱 {{ $siteName }}</div>
            <p>Tu plataforma de comercio agrícola</p>
        </div>

        <div class="celebration">🎉</div>
        
        <h1 class="approved-title">¡Felicitaciones, {{ $sellerName }}!</h1>

        <div class="success-box">
            <h2 style="margin: 0 0 15px 0; font-size: 24px;">✅ Tu cuenta ha sido aprobada</h2>
            <p style="margin: 0; font-size: 18px;">Ya puedes comenzar a vender en {{ $siteName }}</p>
        </div>

        <p style="font-size: 18px; text-align: center;">
            ¡Excelentes noticias! Nuestro equipo ha revisado y <strong>aprobado</strong> tu solicitud de registro como vendedor en {{ $siteName }}.
        </p>

        <div class="info-box">
            <h3>📋 Detalles de tu cuenta:</h3>
            <ul>
                <li><strong>Nombre de la tienda:</strong> {{ $sellerName }}</li>
                <li><strong>Email:</strong> {{ $sellerEmail }}</li>
                <li><strong>Estado:</strong> <span style="color: #28a745; font-weight: bold;">✅ APROBADA</span></li>
                <li><strong>Fecha de aprobación:</strong> {{ now()->format('d/m/Y H:i') }}</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <h3>🚀 ¡Comienza ahora!</h3>
            <a href="{{ $loginUrl }}" class="btn">
                🔐 Iniciar Sesión
            </a>
            <a href="{{ $dashboardUrl }}" class="btn btn-secondary">
                📊 Ir al Panel
            </a>
        </div>

        <div class="next-steps">
            <h3>📝 Primeros pasos recomendados:</h3>
            
            <div class="step">
                <div class="step-number">1</div>
                <div>
                    <strong>Configura tu perfil de tienda</strong>
                    <p style="margin: 5px 0 0 0; color: #666;">Añade descripción, logo y información de contacto</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div>
                    <strong>Publica tus primeros productos</strong>
                    <p style="margin: 5px 0 0 0; color: #666;">Sube fotos, precios y descripciones detalladas</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div>
                    <strong>Configura métodos de pago</strong>
                    <p style="margin: 5px 0 0 0; color: #666;">Añade tus cuentas bancarias y sistemas de pago</p>
                </div>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <div>
                    <strong>¡Comienza a recibir órdenes!</strong>
                    <p style="margin: 5px 0 0 0; color: #666;">Gestiona pedidos desde tu panel administrativo</p>
                </div>
            </div>
        </div>

        <div class="benefits">
            <h3>🎁 Beneficios de ser vendedor en {{ $siteName }}:</h3>
            
            <div class="feature-grid">
                <div class="feature-item">
                    <div class="feature-icon">📈</div>
                    <strong>Aumenta tus ventas</strong>
                    <p style="font-size: 12px; margin: 5px 0 0 0;">Llega a más clientes</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">💳</div>
                    <strong>Pagos seguros</strong>
                    <p style="font-size: 12px; margin: 5px 0 0 0;">Sistema confiable</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <strong>Panel completo</strong>
                    <p style="font-size: 12px; margin: 5px 0 0 0;">Gestiona todo fácilmente</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🤝</div>
                    <strong>Soporte 24/7</strong>
                    <p style="font-size: 12px; margin: 5px 0 0 0;">Estamos aquí para ayudarte</p>
                </div>
            </div>
        </div>

        <div style="background-color: #e3f2fd; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h3 style="color: #1976d2; margin-top: 0;">💬 ¿Necesitas ayuda?</h3>
            <p>Nuestro equipo de soporte está listo para ayudarte en cada paso:</p>
            <ul>
                <li>📧 <strong>Email:</strong> soporte@agroplace.com</li>
                <li>🕒 <strong>Horario:</strong> Lunes a Viernes, 8:00 AM - 6:00 PM</li>
                <li>💬 <strong>Chat en vivo:</strong> Disponible en tu panel</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>{{ $siteName }}</strong> - Conectando productores con consumidores</p>
            <p>¡Gracias por unirte a nuestra comunidad de vendedores!</p>
            <hr>
            <p style="font-size: 12px; color: #999;">
                © {{ date('Y') }} {{ $siteName }}. Todos los derechos reservados.<br>
                Email enviado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
            </p>
        </div>
    </div>
</body>
</html>