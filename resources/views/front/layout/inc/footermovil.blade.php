<div class="sticky-footer sticky-content fix-bottom">
        <a href="{{ url('/') }}" class="sticky-link active">
            <i class="w-icon-home"></i>
            <p>Inicio</p>
        </a>
        <a href="#" class="sticky-link">
            <i class="w-icon-category"></i>
            <p>Tiendas</p>
        </a>
        <a href="{{ url('/cliente/ingresar') }}" class="sticky-link">
            <i class="w-icon-account"></i>
            <p>Mi cuenta</p>
        </a>
        <div class="cart-dropdown dir-up">
            <a href="{{ url('/cliente/carrito') }}" class="sticky-link">
                <i class="w-icon-cart"></i>
                <p>Carrito</p>
            </a>
            <!-- End of Dropdown Box -->
        </div>

        <div class="header-search hs-toggle dir-up">
            <a href="#" class="search-toggle sticky-link">
                <i class="w-icon-search"></i>
                <p>Buscar</p>
            </a>
            <form action="#" class="input-wrapper">
                <input type="text" class="form-control" name="search" autocomplete="off" placeholder="Search"
                    required />
                <button class="btn btn-search" type="submit">
                    <i class="w-icon-search"></i>
                </button>
            </form>
        </div>
    </div>