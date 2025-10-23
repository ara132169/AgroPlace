<div>
@if (Auth::guard('admin')->check())

<div class="user-info-dropdown">
    <div class="dropdown">
        <a
            class="dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
        >
            <span class="user-icon">
                <img src="{{$admin->picture}}" alt="" />
            </span>
            <span class="user-name">{{$admin->name}}</span>
        </a>
        <div
            class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
        >
            <a class="dropdown-item" href="{{route('admin.perfil')}}"
                ><i class="dw dw-user1"></i> Perfil</a
            >
            <a class="dropdown-item" href="{{route('admin.configuraciones')}}"
                ><i class="dw dw-settings2"></i>Configuraciones</a
            >
           
            <a class="dropdown-item" href="{{route('admin.logout_handler')}}"
                onclick="event.preventDefault();document.getElementById('adminLogoutForm').
                submit();"><i class="dw dw-logout"></i> Cerrar sesión</a
            >
            <form action="{{route('admin.logout_handler')}}" id="adminLogoutForm"
            method="POST">@csrf</form><br>
        </div>
    </div>
</div>

@elseif( Auth::guard('seller')->check() )

<div class="user-info-dropdown">
    <div class="dropdown">
        <a
            class="dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
        >
            <span class="user-icon">
                <img src="{{$seller->picture}}" alt="" />
            </span>
            <span class="user-name">{{$seller->name}}</span>
        </a>
        <div
            class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
        >
            <a class="dropdown-item" href="{{route('tienda.perfil')}}"
                ><i class="dw dw-user1"></i> Perfil</a
            >
            <a class="dropdown-item" href="{{route('tienda.configuraciones-tienda')}}"
                ><i class="dw dw-settings2"></i>Configuraciones</a
            >
          
            <a class="dropdown-item" href="{{route('tienda.logout')}}"  onclick="event.preventDefault();
            document.getElementById('sellerLogoutForm').submit();"
                ><i class="dw dw-logout"></i>Cerrar sesión</a
            >

            <form action="{{route('tienda.logout')}}" id="sellerLogoutForm"
            method="POST">@csrf</form><br>
        </div>
    </div>
</div>



@elseif( Auth::guard('client')->check() )

<div class="user-info-dropdown">
    <div class="dropdown">
        <a
            class="dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
        >
            <span class="user-icon">
                <img src="" alt="" />
            </span>
            <span class="user-name"></span>
        </a>
        <div
            class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
        >
            <a class="dropdown-item" href="{{route('cliente.perfil')}}"
                ><i class="dw dw-user1"></i> Perfil</a
            >
            <a class="dropdown-item" href="{{route('cliente.panel')}}"
                ><i class="dw dw-settings2"></i>Configuraciones</a
            >
          
            <a class="dropdown-item" href="{{route('cliente.logout')}}"  onclick="event.preventDefault();
            document.getElementById('sellerLogoutForm').submit();"
                ><i class="dw dw-logout"></i>Cerrar sesión</a
            >

            <form action="{{route('cliente.logout')}}" id="sellerLogoutForm"
            method="POST">@csrf</form><br>
        </div>
    </div>
</div>

@endif
</div>
