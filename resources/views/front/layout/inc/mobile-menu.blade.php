<div class="mobile-menu-wrapper">
        <div class="mobile-menu-overlay"></div>
        <!-- End of .mobile-menu-overlay -->

        <a href="#" class="mobile-menu-close"><i class="close-icon"></i></a>
        <!-- End of .mobile-menu-close -->

        <div class="mobile-menu-container scrollable">
            <form action="#" method="get" class="input-wrapper">
                <input type="text" class="form-control text-white" name="search" autocomplete="off" placeholder="Buscar"
                    required />
                <button class="btn btn-search" type="submit">
                    <i class="w-icon-search"></i>
                </button>
            </form>
            <!-- End of Search Form -->
            <div class="tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#main-menu" class="nav-link active">Menú principal</a>
                    </li>
                    <li class="nav-item">
                        <a href="#categories" class="nav-link">Categorías</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="main-menu">
                    <ul class="mobile-menu">
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li>
                            <a href="vendor-dokan-store.html">Tiendas</a>
                        </li>
                        <li>
                            <a href="blog-mask-grid.html">Blog</a>
                           
                        </li>
                        <li>
                            <a href="{{ url('nosotros') }}">Nosotros</a>
                           
                        </li>
                        <li>
                            <a href="{{ url('contacto') }}">Contacto</a>
                          
                        </li>
                        <li>
                            <a href="{{ url('/cliente/ingresar') }}">Inicia sesión como usuario</a>
                          
                        </li>
                        <li>
                            <a href="{{ url('/tienda/ingresar') }}">Inicia sesión en tu tienda</a>
                          
                        </li>
                    </ul>
                </div>
                <div class="tab-pane" id="categories">
                    <ul class="mobile-menu">
                    @if(count(get_categories())>0)
                    @foreach(get_categories() as $category)
                        <li>
                            <a href="{{ route('categoria.productos', ['slug' => $category->category_slug]) }}">
                                <i class="/images/categories/{{$category->category_image}}"></i> {{$category->category_name}}
                                                @if( count($category->subcategories) > 0 )
                                                    
                                                @endif
                            </a>
                            <ul>
                            @if(count($category->subcategories) > 0 )
                                <li>
                                @foreach($category->subcategories as $subcategory )
                                @if($subcategory->is_child_of == 0)
                                    <a href="">{{ $subcategory->subcategory_name }}</a>
                                    @if(!empty($subcategory->children) && $subcategory->children->count())
                                    <ul>
                                    @foreach($subcategory->children as $child_subcategory)
                                        <li><a href="{{ route('subcategoria.productos', ['slug' => $child_subcategory->subcategory_slug]) }}">{{ $child_subcategory->subcategory_name }}</a>
                                        </li>
                                        @endforeach   
                                    </ul>
                                    @endif
                                @endif
                                @endforeach
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endforeach
                        @endif()
                    </ul>
                </div>

            </div>
        </div>
    </div>