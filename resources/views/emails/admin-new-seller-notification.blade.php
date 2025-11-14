<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud de Tienda - {{ $siteName }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
        }
        .alert-title {
            color: #dc3545;
            font-size: 22px;
            margin-bottom: 20px;
            text-align: center;
        }
        .seller-info {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .urgent {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .actions {
            background-color: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            text-align: center;
        }
        .stat-item {
            flex: 1;
            padding: 10px;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .priority-high {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🔔 {{ $siteName }} Admin</div>
            <p>Sistema de Notificaciones Administrativas</p>
        </div>

        <h1 class="alert-title">🆕 Nueva Solicitud de Registro</h1>

        <div class="urgent">
            <h3>⚠️ Acción Requerida</h3>
            <p>Se ha registrado una nueva tienda que requiere <span class="priority-high">revisión y aprobación</span> del administrador.</p>
        </div>

        <div class="seller-info">
            <h3>👤 Información del Solicitante:</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Nombre de la Tienda:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $sellerName }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Correo Electrónico:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $sellerEmail }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>ID de Registro:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">#{{ $sellerId }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Fecha de Registro:</strong></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $registrationDate }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Estado Actual:</strong></td>
                    <td style="padding: 8px;"><span style="background-color: #ffc107; color: #000; padding: 4px 8px; border-radius: 12px; font-size: 12px;">Pendiente de Verificación</span></td>
                </tr>
            </table>
        </div>

        <div class="actions">
            <h3>🛠️ Acciones Disponibles:</h3>
            <p>Puedes revisar y gestionar esta solicitud desde el panel administrativo:</p>
            
            <a href="{{ $adminPanelUrl }}" class="btn btn-primary">
                🔍 Ver Panel de Vendedores
            </a>
            
            <br><br>
            
            <p style="font-size: 14px; color: #666;">
                Desde el panel podrás:
            </p>
            <ul style="text-align: left; display: inline-block;">
                <li>✅ Aprobar la solicitud</li>
                <li>❌ Rechazar la solicitud</li>
                <li>📧 Contactar al solicitante</li>
                <li>👀 Ver detalles completos</li>
            </ul>
        </div>

        <div style="background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="color: #1976d2; margin-top: 0;">📊 Recordatorio:</h3>
            <p>Es importante revisar las solicitudes en un plazo de <strong>1-3 días hábiles</strong> para mantener una buena experiencia del usuario.</p>
            <p>El solicitante ha sido notificado automáticamente sobre el estado de su registro.</p>
        </div>

        <div class="footer">
            <p><strong>{{ $siteName }} - Panel Administrativo</strong></p>
            <p>Este es un correo automático del sistema de notificaciones.</p>
            <hr>
            <p style="font-size: 12px; color: #999;">
                © {{ date('Y') }} {{ $siteName }}. Todos los derechos reservados.<br>
                Notificación enviada el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
            </p>
        </div>
    </div>
</body>
</html>