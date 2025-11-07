<!DOCTYPE html>
<html lang="en">

<head>
@include('front.layout.inc.head')
<style>
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
        position: relative;
    }
    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
    .alert-dismissible {
        padding-right: 35px;
    }
    .btn-close {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        padding: 1.25rem 1rem;
        color: inherit;
        background: transparent;
        border: 0;
        font-size: 1.125rem;
        cursor: pointer;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
        display: block;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
</style>
</head>

<body>
    <div class="page-wrapper">
        <!-- Start of Header -->
        @include('front.layout.inc.headerdos')
        <!-- End of Header -->


        <!-- Start of Main -->
        <main class="main">
            <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Contacto</h1>
                </div>
            </div>
            <!-- End of Page Header -->

            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav mb-10 pb-1">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li>Contacto</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content contact-us">
                <div class="container">
                    <section class="content-title-section mb-10">
                        <h3 class="title title-center mb-3">Información de contacto
                        </h3>
                        <p class="text-center">¿Tienes dudas? Nuestro equipo de AgroPlace está para atender tus necesidades.</p>
                    </section>
                    <!-- End of Contact Title Section -->

                    <section class="contact-information-section mb-10">
                        <div class=" swiper-container swiper-theme " data-swiper-options="{
                            'spaceBetween': 20,
                            'slidesPerView': 1,
                            'breakpoints': {
                                '480': {
                                    'slidesPerView': 2
                                },
                                '768': {
                                    'slidesPerView': 3
                                },
                                '992': {
                                    'slidesPerView': 4
                                }
                            }
                        }">
                            <div class="swiper-wrapper row cols-xl-4 cols-md-3 cols-sm-2 cols-1">
                                <div class="swiper-slide icon-box text-center icon-box-primary">
                                    <span class="icon-box-icon icon-email">
                                        <i class="w-icon-envelop-closed"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">Correo Electrónico</h4>
                                        <p>{{ get_settings()->site_email ?? 'contacto@agroplace.com' }}</p>
                                    </div>
                                </div>
                                <div class="swiper-slide icon-box text-center icon-box-primary">
                                    <span class="icon-box-icon icon-headphone">
                                        <i class="w-icon-headphone"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">WhatsApp</h4>
                                        <p>{{ get_settings()->site_phone ?? '(123) 456-9870' }}</p>
                                    </div>
                                </div>
                                <div class="swiper-slide icon-box text-center icon-box-primary">
                                    <span class="icon-box-icon icon-map-marker">
                                        <i class="w-icon-map-marker"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">Ubicación</h4>
                                        <p>{{ get_settings()->site_address ?? 'México' }}</p>
                                    </div>
                                </div>
                                <div class="swiper-slide icon-box text-center icon-box-primary">
                                    <span class="icon-box-icon icon-fax">
                                        <i class="w-icon-fax"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title">Soporte</h4>
                                        <p>Nuestro equipo está para resolver tus dudas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End of Contact Information section -->

                    <hr class="divider mb-10 pb-1">

                    <section class="contact-section">
                        <div class="row gutter-lg pb-3">
                            <div class="col-lg-6 mb-8">
                                <h4 class="title mb-3">Preguntas frecuentes</h4>
                                <div class="accordion accordion-bg accordion-gutter-md accordion-border">
                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#collapse1" class="collapse">¿Cómo puedo cancelar mi pedido?</a>
                                        </div>
                                        <div id="collapse1" class="card-body expanded">
                                            <p class="mb-0">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod temp orincid 
                                                idunt ut labore et dolore magna aliqua. Venenatis tellus in metus vulp utate eu sceler 
                                                isque felis. Vel pretium.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#collapse2" class="expand">¿Puedo comprar sin registrarme?</a>
                                        </div>
                                        <div id="collapse2" class="card-body collapsed">
                                            <p class="mb-0">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod temp orincid 
                                                idunt ut labore et dolore magna aliqua. Venenatis tellus in metus vulp utate eu sceler 
                                                isque felis. Vel pretium.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#collapse3" class="expand">¿Realizan envios a todo México?</a>
                                        </div>
                                        <div id="collapse3" class="card-body collapsed">
                                            <p class="mb-0">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod temp orincid 
                                                idunt ut labore et dolore magna aliqua. Venenatis tellus in metus vulp utate eu sceler 
                                                isque felis. Vel pretium.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#collapse4" class="expand">¿Cómo puedo rastrear mi pedido?</a>
                                        </div>
                                        <div id="collapse4" class="card-body collapsed">
                                            <p class="mb-0">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod temp orincid 
                                                idunt ut labore et dolore magna aliqua. Venenatis tellus in metus vulp utate eu sceler 
                                                isque felis. Vel pretium.
                                            </p>
                                        </div>
                                    </div>

                                 
                                </div>
                            </div>
                            <div class="col-lg-6 mb-8">
                                <h4 class="title mb-3">Envianos un mensaje</h4>
                                
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                
                                <form class="form contact-us-form" action="{{ route('contacto.enviar') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="username">Nombre completo *</label>
                                        <input type="text" id="username" name="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username') }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email_1">Correo electrónico *</label>
                                        <input type="email" id="email_1" name="email_1"
                                            class="form-control @error('email_1') is-invalid @enderror"
                                            value="{{ old('email_1') }}" required>
                                        @error('email_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Mensaje *</label>
                                        <textarea id="message" name="message" cols="30" rows="5"
                                            class="form-control @error('message') is-invalid @enderror"
                                            required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-rounded">
                                        <i class="w-icon-envelop"></i> ENVIAR MENSAJE
                                    </button>
                                </form>
                            </div>
                        </div>
                    </section>
                    <!-- End of Contact Section -->
                </div>

                
            </div>
            <!-- End of PageContent -->
        </main>
        <!-- End of Main -->

        <!-- Start of Footer -->
         @include('front.layout.inc.footer')
        <!-- End of Footer -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Start of Sticky Footer -->
    @include('front.layout.inc.footermovil')
    <!-- End of Sticky Footer -->
    
    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <!-- Start of Mobile Menu -->
    @include('front.layout.inc.mobile-menu')
    <!-- End of Mobile Menu -->

    <!-- Plugin JS File -->
    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/front/assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/front/assets/js/main.min.js"></script>

  
</body>

</html>