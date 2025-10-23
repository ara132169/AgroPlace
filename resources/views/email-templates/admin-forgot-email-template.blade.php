<p>Estimado {{$admin->name}}</p>
<p>
    Hemos recibido una solicitud para reestablecer la contraseña desde agromarketplace.com con el correo {{$admin->email}}.
    Puedes reestablecer tu contraseña dando clic en el botón de abajo:
    <br>

    <a href="{{$actionLink}}" target="_blank" style="color:white;border-color:#22bc66;
    border-style:solid; border-width:5px; background-color:#22bc66; display:inline-block;text-decoration:none;
    border-radius:3px; box-shadow:0 2px 3px rgba(0,0,0,0,16);-webkit-text-size-adjust:none;
    box-sizing:border-box;">Reestablecer contraseña</a>

    <br>
    <b>Nota:</b> Este link tiene una duración de 15 minutos.
    <br>

    Si tu no solicitaste reestablecer tu contraseña, ignora este mensaje.
</p>