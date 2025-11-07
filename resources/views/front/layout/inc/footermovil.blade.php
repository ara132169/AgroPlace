<div class="sticky-footer sticky-content fix-bottom">
        <a href="{{ url('/') }}" class="sticky-link active">
            <i class="w-icon-home"></i>
            <p>Inicio</p>
        </a>
        <a href="{{ route('tiendas.index') }}" class="sticky-link">
            <i class="w-icon-category"></i>
            <p>Tiendas</p>
        </a>
        @if(Auth::guard('client')->check())
            <a href="{{ route('cliente.panel') }}" class="sticky-link">
                <i class="w-icon-account"></i>
                <p>Mi cuenta</p>
            </a>
        @else
            <a href="{{ url('/cliente/ingresar') }}" class="sticky-link">
                <i class="w-icon-account"></i>
                <p>Mi cuenta</p>
            </a>
        @endif
        <div class="cart-dropdown dir-up">
            <a href="{{ url('/cliente/carrito') }}" class="sticky-link">
                <i class="w-icon-cart"></i>
                <p>Carrito</p>
            </a>
            <!-- End of Dropdown Box -->
        </div>

        
    </div>