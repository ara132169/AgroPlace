@component('mail::message')
{{-- Logo personalizado del sitio --}}
@if($siteSettings->site_logo)
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="{{ $siteSettings->site_name }}" style="max-width: 200px; height: auto;">
</div>
@endif

# ¡Bienvenido a {{ $siteSettings->site_name }}, {{ $client->name }}!

Nos complace informarte que tu cuenta ha sido creada exitosamente en nuestra plataforma.

## Detalles de tu cuenta:
- **Nombre:** {{ $client->name }}
- **Nombre de usuario:** {{ $client->username }}
- **Correo electrónico:** {{ $client->email }}
- **Fecha de registro:** {{ $client->created_at->format('d/m/Y H:i') }}

## ¿Qué puedes hacer ahora?

✅ **Explorar productos:** Navega por nuestra amplia variedad de productos agrícolas  
✅ **Realizar compras:** Encuentra los mejores productos al mejor precio  
✅ **Gestionar tu perfil:** Actualiza tu información personal cuando lo necesites  
✅ **Historial de compras:** Revisa todas tus transacciones  

@component('mail::button', ['url' => route('cliente.ingresar')])
Acceder a mi cuenta
@endcomponent

## ¿Necesitas ayuda?

Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos:

- **Email:** {{ $siteSettings->site_email }}
- **Teléfono:** {{ $siteSettings->site_phone }}
- **Horario de atención:** Lunes a Viernes, 9:00 AM - 6:00 PM

¡Gracias por unirte a nuestra comunidad agrícola!

Saludos cordiales,  
**El equipo de {{ $siteSettings->site_name }}**

---
*Este es un correo automático, por favor no respondas a este mensaje.*

@endcomponent
