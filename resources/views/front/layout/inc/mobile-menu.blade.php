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
                            <a href="{{ route('tiendas.index') }}">Tiendas</a>
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
                                @php $categoryImage = get_category_image($category); @endphp
                                @if($categoryImage)
                                    <img src="{{ $categoryImage }}" alt="{{ $category->category_name }}" width="20" height="20" style="margin-right: 8px; vertical-align: middle;">
                                @else
                                    <i class="w-icon-category" style="margin-right: 8px; font-size: 16px; vertical-align: middle;"></i>
                                @endif
                                {{$category->category_name}}
                                                @if( count($category->subcategories) > 0 )
                                                    
                                                @endif
                            </a>
                            <ul>
                            @if(count($category->subcategories) > 0 )
                                <li>
                                @foreach($category->subcategories as $subcategory )
                                @if($subcategory->is_child_of == 0)
                                    <a href="{{ route('subcategoria', ['categorySlug' => $category->category_slug, 'subcategorySlug' => $subcategory->subcategory_slug]) }}">{{ $subcategory->subcategory_name }}</a>
                                    @if(!empty($subcategory->children) && $subcategory->children->count())
                                    <ul>
                                    @foreach($subcategory->children as $child_subcategory)
                                        <li><a href="{{ route('subsubcategoria', ['categorySlug' => $category->category_slug, 'subcategorySlug' => $subcategory->subcategory_slug, 'subsubcategorySlug' => $child_subcategory->subcategory_slug]) }}">{{ $child_subcategory->subcategory_name }}</a>
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