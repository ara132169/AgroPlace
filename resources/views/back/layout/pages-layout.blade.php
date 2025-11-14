<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>@yield('pageTitle')</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		

		<!-- Site favicon -->
		
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="/images/site/{{get_settings()->site_favicon}}"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="/back/vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />

		<link rel="stylesheet" href="/extra-assets/ijaboCropTool/ijaboCropTool.min.css">
		<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.1/jquery-ui-1.14.1/jquery-ui.min.css">
		<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.1/jquery-ui-1.14.1/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.1/jquery-ui-1.14.1/jquery-ui.theme.min.css">
		<link rel="stylesheet" href="/extra-assets/summernote/summernote-bs4.min.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

		<style>
			.swal2-popup{
				font-size: 0.8em;
			}
			
			/* Estilos para el menú dropdown que funciona correctamente */
			#accordion-menu .dropdown > ul.submenu {
				display: none;
				background-color: #f8f9fa;
				margin-left: 20px;
				border-left: 2px solid #007bff;
				transition: all 0.3s ease;
			}
			
			#accordion-menu .dropdown.show > ul.submenu {
				display: block;
			}
			
			#accordion-menu .dropdown > a.dropdown-toggle:after {
				content: '▼';
				float: right;
				font-size: 0.8em;
				transition: transform 0.3s ease;
			}
			
			#accordion-menu .dropdown.show > a.dropdown-toggle:after {
				transform: rotate(180deg);
			}
		</style>
		@kropifyStyles 
		@livewireStyles
         @stack('stylesheets')


		 <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
	</head>
	<body>

    
		{{--<div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="/back/vendors/images/AGROMARKET_.jpg" alt="" />
				</div>
				<div class="loader-progress" id="progress_div">
					<div class="bar" id="bar1"></div>
				</div>
				<div class="percent" id="percent1">0%</div>
				<div class="loading-text">Cargando...</div>
			</div>
		</div>--}}

		<div class="header">
			<div class="header-left">
				<div class="menu-icon bi bi-list"></div>
				<div
					class="search-toggle-icon bi bi-search"
					data-toggle="header_search"
				></div>
				
			</div>
			<div class="header-right">
				<div class="dashboard-setting user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="javascript:;"
							data-toggle="right-sidebar"
						>
							<i class="dw dw-settings2"></i>
						</a>
					</div>
				</div>
				<!-- <div class="user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
							<i class="icon-copy dw dw-notification"></i>
							<span class="badge notification-active"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<div class="notification-list mx-h-350 customscroll">
								<ul>
									<li>
										<a href="#">
											<img src="/back/vendors/images/img.jpg" alt="" />
											<h3>John Doe</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/back/vendors/images/photo1.jpg" alt="" />
											<h3>Lea R. Frith</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/back/vendors/images/photo2.jpg" alt="" />
											<h3>Erik L. Richards</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/back/vendors/images/photo3.jpg" alt="" />
											<h3>John Doe</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/back/vendors/images/photo4.jpg" alt="" />
											<h3>Renee I. Hansen</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
									<li>
										<a href="#">
											<img src="/back/vendors/images/img.jpg" alt="" />
											<h3>Vicki M. Coleman</h3>
											<p>
												Lorem ipsum dolor sit amet, consectetur adipisicing
												elit, sed...
											</p>
										</a>
									</li>
								</ul>
							</div>
						</div> 
					</div>
				</div>-->

				{{--<livewire:admin-seller-header-profile-info></livewire:admin-seller-header-profile-info> --}}
				@livewire('admin-seller-header-profile-info')

			



				<div class="github-link">
					<a href="https://github.com/dropways/deskapp" target="_blank"
						><img src="" alt=""
					/></a>
				</div>
			</div>
		</div>

		<div class="right-sidebar">
			<div class="sidebar-title">
				<h3 class="weight-600 font-16 text-blue">
					Configuraciones internas
					<span class="btn-block font-weight-400 font-12"
						>Configuraciones del perfil de usuario</span
					>
				</h3>
				<div class="close-sidebar" data-toggle="right-sidebar-close">
					<i class="icon-copy ion-close-round"></i>
				</div>
			</div>
			<div class="right-sidebar-body customscroll">
				<div class="right-sidebar-body-content">
					<h4 class="weight-600 font-18 pb-10">Fondo Header</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary header-white active"
							>White</a
						>
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary header-dark"
							>Dark</a
						>
					</div>

					<h4 class="weight-600 font-18 pb-10">Fondo Sidebar</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-light"
							>White</a
						>
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-dark active"
							>Dark</a
						>
					</div>

					<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icono</h4>
					<div class="sidebar-radio-group pb-10 mb-10">
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-1"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-1"
								checked=""
							/>
							<label class="custom-control-label" for="sidebaricon-1"
								><i class="fa fa-angle-down"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-2"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-2"
							/>
							<label class="custom-control-label" for="sidebaricon-2"
								><i class="ion-plus-round"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-3"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-3"
							/>
							<label class="custom-control-label" for="sidebaricon-3"
								><i class="fa fa-angle-double-right"></i
							></label>
						</div>
					</div>

					<h4 class="weight-600 font-18 pb-10">Menu Lista Icono</h4>
					<div class="sidebar-radio-group pb-30 mb-10">
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-1"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-1"
								checked=""
							/>
							<label class="custom-control-label" for="sidebariconlist-1"
								><i class="ion-minus-round"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-2"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-2"
							/>
							<label class="custom-control-label" for="sidebariconlist-2"
								><i class="fa fa-circle-o" aria-hidden="true"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-3"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-3"
							/>
							<label class="custom-control-label" for="sidebariconlist-3"
								><i class="dw dw-check"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-4"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-4"
								checked=""
							/>
							<label class="custom-control-label" for="sidebariconlist-4"
								><i class="icon-copy dw dw-next-2"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-5"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-5"
							/>
							<label class="custom-control-label" for="sidebariconlist-5"
								><i class="dw dw-fast-forward-1"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-6"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-6"
							/>
							<label class="custom-control-label" for="sidebariconlist-6"
								><i class="dw dw-next"></i
							></label>
						</div>
					</div>

					<div class="reset-options pt-30 text-center">
						<button class="btn btn-danger" id="reset-settings">
							Reestablecer cambios
						</button>
					</div>
				</div>
			</div>
		</div>

		<div class="left-side-bar">
		@if (Route::is('admin.*'))
			<div class="brand-logo">
				<a href="{{route('admin.home')}}">
					<img src="/images/site/{{ get_settings()->site_logo}}" alt="" class="dark-logo" />
					<img
						src="/images/site/{{ get_settings()->site_logo}}"
						alt=""
						class="light-logo"
					/>
				</a>
				<div class="close-sidebar" data-toggle="left-sidebar-close">
					<i class="ion-close-round"></i>
				</div>
			</div>
		@elseif (Route::is('tienda.*'))
		<div class="brand-logo">
				<a href="{{route('tienda.home')}}">
					<img src="/images/site/{{ get_settings()->site_logo}}" alt="" class="dark-logo" />
					<img
						src="/images/site/{{ get_settings()->site_logo}}"
						alt=""
						class="light-logo"
					/>
				</a>
				<div class="close-sidebar" data-toggle="left-sidebar-close">
					<i class="ion-close-round"></i>
				</div>
			</div>
			@elseif (Route::is('cliente.*'))
			<div class="brand-logo">
				<a href="{{route('inicio')}}">
					<img src="/images/site/{{ get_settings()->site_logo}}" alt="" class="dark-logo" />
					<img
						src="/images/site/{{ get_settings()->site_logo}}"
						alt=""
						class="light-logo"
					/>
				</a>
				<div class="close-sidebar" data-toggle="left-sidebar-close">
					<i class="ion-close-round"></i>
				</div>
			</div>
			@endif

			<div class="menu-block customscroll">
				<div class="sidebar-menu">
					<ul id="accordion-menu">

					@if (Route::is('admin.*'))
					
					<li>
							<a href="{{route('admin.home')}}" class="dropdown-toggle no-arrow 
							{{Route::is('admin.home') ? 'active' : ''}}">
								<span class="micon fa fa-home"></span
								><span class="mtext">Inicio</span>
							</a>
						</li>

						<li>
							<a href="{{ route('admin.manage-categories.cats-subcats-list') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.manage-categories.*') ? 'active' : '' }}">
								<span class="micon dw dw-align-left3"></span
								><span class="mtext">Administrar Categorías</span>
								</a>
						
						</li>

						<li>
							<a href="{{ route('admin.ventas') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.ventas') || Route::is('admin.venta.detalle') ? 'active' : '' }}">
								<span class="micon bi bi-graph-up"></span
								><span class="mtext">Ventas</span>
							</a>
						</li>

						<li>
							<a href="{{ route('admin.depositos') }}" class="dropdown-toggle no-arrow {{ Route::is('admin.depositos*') || Route::is('admin.cuentas-pago*') || Route::is('admin.cuentas-vendedores*') ? 'active' : '' }}">
								<span class="micon bi bi-credit-card"></span
								><span class="mtext">💰 Gestión Depósitos</span>
							</a>
						</li>

					
						<li>
							<div class="dropdown-divider"></div>
						</li>
						<li>
							<div class="sidebar-small-cap">Configuración</div>
						</li>
					
						<li>
							<a
								href="{{route('admin.perfil')}}"
								
								class="dropdown-toggle no-arrow {{Route::is('admin.perfil') ? 'active' : ''}}"
							>
								<span class="micon fa fa-user"></span>
								<span class="mtext"
									>Perfil
									</span>
							</a>
						</li>

						<li>
							<a
								href="{{route('admin.configuraciones')}}"
								
								class="dropdown-toggle no-arrow {{Route::is('admin.configuraciones') ? 'active' : ''}}"
							>
								<span class="micon icon-copy fi-widget"></span>
								<span class="mtext"
									>Configuraciones
									</span>
							</a>
						</li>

						
					@elseif(Route::is('tienda.*'))
					<li>
							<a href="{{route('tienda.home')}}" class="dropdown-toggle no-arrow {{ Route::is('tienda.home') ? 'active' : '' }}">
								<span class="micon fa fa-home"></span
								><span class="mtext">Inicio</span>
							</a>
						</li>
						

						<li>
							<a href="{{ route('tienda.ventas') }}" class="dropdown-toggle no-arrow {{ Route::is('tienda.ventas') || Route::is('tienda.venta.detalle') ? 'active' : '' }}">
								<span class="micon bi bi-graph-up"></span
								><span class="mtext">Ventas</span>
							</a>
						</li>

						<li>
							<a href="{{ route('tienda.compras') }}" class="dropdown-toggle no-arrow {{ Route::is('tienda.compras') || Route::is('tienda.compra.detalle') ? 'active' : '' }}">
								<span class="micon bi bi-shopping-cart"></span
								><span class="mtext">Compras</span>
							</a>
						</li>

						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle {{ Route::is('tienda.product.*') ? 'active' : '' }}">
								<span class="micon bi bi-bag"></span><span class="mtext">Administrar productos</span>
							</a>
							<ul class="submenu">
								<li><a href="{{ route('tienda.product.productos') }}" class="{{ Route::is('tienda.product.productos') ? 'active' : '' }}">Todos los productos</a></li>
								<li><a href="{{ route('tienda.product.agregar-productos') }}" class="{{ Route::is('tienda.product.agregar-productos') ? 'active' : '' }}">Añadir nuevo producto</a></li>
							</ul>
						</li>

						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle {{ Route::is('tienda.stripe.*') || Route::is('tienda.payment.*') || Route::is('tienda.deposit.*') ? 'active' : '' }}">
								<span class="micon bi bi-credit-card"></span>
								<span class="mtext">� Gestión de Pagos
									@auth('seller')
										@if(auth('seller')->user()->hasPaymentAccount())
											<span class="badge badge-success badge-pill ml-1" style="font-size: 10px;">✓</span>
										@else
											<span class="badge badge-warning badge-pill ml-1" style="font-size: 10px;">!</span>
										@endif
									@endauth
								</span>
							</a>
							<ul class="submenu">
								<li><a href="{{ route('tienda.stripe.config') }}" class="{{ Route::is('tienda.stripe.*') ? 'active' : '' }}">🎯 Stripe Connect</a></li>
								<li><a href="{{ route('tienda.payment.accounts') }}" class="{{ Route::is('tienda.payment.*') ? 'active' : '' }}">💳 Mis Cuentas</a></li>
								<li><a href="{{ route('tienda.deposit.history') }}" class="{{ Route::is('tienda.deposit.*') ? 'active' : '' }}">📊 Historial Depósitos</a></li>
							</ul>
						</li>

						<li>
							<div class="dropdown-divider"></div>
						</li>
						<li>
							<div class="sidebar-small-cap">Configuración</div>
						</li>
					
						<li>
							<a
								href="{{route('tienda.perfil')}}" class="dropdown-toggle no-arrow {{Route::is('tienda.perfil') ? 'active' : ''}}" >
								<span class="micon fa fa-user"></span>
								<span class="mtext"
									>Perfil
									</span>
							</a>
						</li>

						<li>
							<a
								href="{{route('tienda.configuraciones-tienda')}}" class="dropdown-toggle no-arrow {{Route::is('tienda.configuraciones-tienda') ? 'active' : ''}}" >
								<span class="micon bi bi-shop"></span>
								<span class="mtext"
									>Configura tu tienda
									</span>
							</a>
						</li>
					@else
						<li>
							<a href="{{ route('cliente.panel') }}" class="dropdown-toggle no-arrow {{ Route::is('cliente.panel') ? 'active' : '' }}">
								<span class="micon fa fa-home"></span
								><span class="mtext">Inicio</span>
							</a>
						</li>
						<li>
							<a href="{{ route('cliente.compras') }}" class="dropdown-toggle no-arrow {{ Route::is('cliente.compras') ? 'active' : '' }}">
								<span class="micon bi bi-receipt-cutoff"></span
								><span class="mtext">Pedidos</span>
							</a>
						</li>
						<li>
							<div class="dropdown-divider"></div>
						</li>
						<li>
							<div class="sidebar-small-cap">CONFIGURACIÓN</div>
						</li>
					
						<li>
							<a
								href="{{ route('cliente.perfil') }}" class="dropdown-toggle no-arrow {{ Route::is('cliente.perfil') ? 'active' : '' }}" >
								<span class="micon fa fa-user"></span>
								<span class="mtext"
									>Perfil
									</span>
							</a>
						</li>

						

					@endif
					
					</ul>
				</div>
			</div>
		</div>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<div >
                        @yield('content')
                    </div>
				</div>
				<div class="footer-wrap pd-20 mb-20 card-box">
					agromarketplace.com
					
				</div>
			</div>
		</div>

		<!-- js -->
		<script src="/back/vendors/scripts/core.js"></script>
		<script src="/back/vendors/scripts/script.js"></script>
		<script src="/back/vendors/scripts/process.js"></script>
		<script src="/back/vendors/scripts/layout-settings.js"></script>
		<script src="/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>
		<script src="/extra-assets/jquery-ui-1.14.1/jquery-ui-1.14.1/jquery-ui.min.js"></script>
		<script src="/extra-assets/summernote/summernote-bs4.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

		

		<script>
			$(document).ready(function(){
                $('.summernote').summernote({
					height:200
				});

				// Solución simple y confiable para el menú dropdown
				$('#accordion-menu .dropdown > a.dropdown-toggle').on('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					
					var $dropdown = $(this).parent('.dropdown');
					var $submenu = $dropdown.find('ul.submenu');
					
					console.log("Click en menú dropdown - elemento:", $dropdown.length);
					
					// Cerrar otros dropdowns
					$('#accordion-menu .dropdown').not($dropdown).removeClass('show');
					$('#accordion-menu .dropdown').not($dropdown).find('ul.submenu').slideUp(300);
					
					// Toggle el dropdown actual
					if ($dropdown.hasClass('show')) {
						$dropdown.removeClass('show');
						$submenu.slideUp(300);
						console.log("Cerrando dropdown");
					} else {
						$dropdown.addClass('show');
						$submenu.slideDown(300);
						console.log("Abriendo dropdown");
					}
				});

				console.log("Sistema de menú dropdown inicializado");
			});
		</script>
		<script>
			window.addEventListener('showToastr', function(event){
                  toastr.remove();
				  if( event.detail[0].type === 'info' ){ toastr.info(event.detail[0].message); }
				  else if( event.detail[0].type === 'success' ){ toastr.success(event.detail[0].message); }
				  else if( event.detail[0].type === 'error' ){ toastr.error(event.detail[0].message); }
				  else if( event.detail[0].type === 'warning' ){ toastr.warning(event.detail[0].message); }
				  else{ return false; }
			});
		</script>
		<script>
    document.addEventListener('livewire:load', () => {
        Livewire.on('mostrarBootstrapToast', ({title, message}) => {
            document.getElementById('toast-title').innerText = title;
            document.getElementById('toast-message').innerText = message;
            const toast = new bootstrap.Toast(document.getElementById('livewire-toast'));
            toast.show();
        });
    }); 
</script>
		@kropifyScripts
		@livewireScripts
        @stack('scripts')
	</body>
</html>
