<div>
<div class="tab">
									<ul class="nav nav-pills" role="tablist">
										<li class="nav-item">
											<a  wire:click.prevent='selectTab("general_settings")'  class="nav-link text-blue {{ $tab == 'general_settings' ? 'active' : '' }}"
                                             data-toggle="tab" href="#general_settings" role="tab" aria-selected="false">Configuraciones generales</a>
										</li>
										<li class="nav-item">
											<a wire:click.prevent='selectTab("logo_favicon")' class="nav-link text-blue {{ $tab == 'logo_favicon' ? 'active' : '' }}" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="true">Logo & Favicon</a>
										</li>
										<li class="nav-item">
											<a wire:click.prevent='selectTab("social_networks")' class="nav-link text-blue  {{ $tab == 'social_networks' ? 'active' : '' }}" data-toggle="tab" href="#social_networks" role="tab" aria-selected="true">Redes sociales</a>
										</li>
                                        <li class="nav-item">
											<a wire:click.prevent='selectTab("payment_methods")' class="nav-link text-blue {{ $tab == 'payment_methods' ? 'active' : '' }} " data-toggle="tab" href="#payment_methods" role="tab" aria-selected="true">Imágenes principales</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade {{ $tab == 'general_settings' ? 'active show' : '' }}" id="general_settings" role="tabpanel">
											<div class="pd-20">
												<form wire:submit='updateGeneralSettings()'>
                                                <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for=""><b>Nombre del sitio</b></label>
                                                                <input type="text" class="form-control" placeholder="Ingresa el nombre del sitio" wire:model='site_name'>
                                                                @error('site_name') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for=""><b>Correo de contacto del sitio</b></label>
                                                                <input type="text" class="form-control" placeholder="Ingresa el correo de contacto" wire:model='site_email'>
                                                                @error('site_email') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for=""><b>Teléfono del sitio</b></label>
                                                                <input type="text" class="form-control" placeholder="Ingresa tu número de contacto" wire:model='site_phone'>
                                                                @error('site_phone') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for=""><b>Meta keywords</b><small> Separados por coma (a,b,c)</small></label>
                                                                <input type="text" class="form-control" placeholder="Ingresa meta keywords" wire:model='site_meta_keywords'>
                                                                @error('site_meta_keywords') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                        </div>
                                                 </div>
                                               
                                                    <div class="form-group">
                                                        <label for="">Descripción meta del sitio</label>
                                                        <textarea  cols="4" rows="4" placeholder="Descrición meta" class="form-control" wire:model='site_meta_description'></textarea>
                                                        @error('site_meta_description') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                </form>
											</div>
										</div>
										<div class="tab-pane fade {{ $tab == 'logo_favicon' ? 'active show' : '' }}" id="logo_favicon" role="tabpanel">
											<div class="pd-20">
                                                 <div class="row">
                                                    <div class="col-md-6">
                                                        <h5>Logo del sitio</h5>
                                                        <div class="mb-2 mt-1" style="max-width: 200px;">
                                                                <img wire:ignore src="/images/site/{{ $site_logo }}" class="img-thumbnail" data-ijabo-default-img=
                                                                "/images/site/{{ $site_logo }}" id="site_logo_image_preview">
                                                        </div>
                                                        <form action="{{ route('admin.change-logo') }}" method="POST" enctype="multipart/form-data" id="change_site_logo_form">
                                                            @csrf
                                                            <div class="mb-2">
                                                                <input type="file" name="site_logo" id="site_logo" class="form-control">
                                                                <span class="text-danger error-text site_logo_error"></span>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Cambiar logo</button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5>Favicon del sitio</h5>
                                                        <div class="mb-2 mt-1" style="max-width: 100px;">
                                                            <img wire:ignore src="" alt="" class="img-thumbnail" id="site_favicon_image_preview" data-ijabo-default-img="/images/site/{{ $site_favicon }}">
                                                        </div>
                                                        <form action="{{ route('admin.change-favicon') }}" method="post" enctype="multipart/form-data" id="change_site_favicon_form">
                                                        @csrf
                                                        <div class="mb-2">
                                                            <input type="file" name="site_favicon" id="site_favicon" class="form-control">
                                                            <span class="text-danger error-text site_favicon_error"></span>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Cambiar favicon</button>
                                                        </form>
                                                    </div>
                                                 </div>
                                                 
											</div>
										</div>
										<div class="tab-pane fade {{ $tab == 'social_networks' ? 'active show' : '' }}" id="social_networks" role="tabpanel">
											<div class="pd-20">
                                            <form wire:submit='updateSocialNetworks()'>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for=""><b>Facebook URL</b></label>
                                                            <input type="text" class="form-control" wire:model='facebook_url' placeholder="Ingresa la URL">
                                                            @error('facebook_url'){{ $message }}@enderror
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for=""><b>Instagram URL</b></label>
                                                            <input type="text" class="form-control" wire:model='instagram_url' placeholder="Ingresa la URL">
                                                            @error('instagram_url'){{ $message }}@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                </div>
                                            </form>
											</div>
										</div>

                                        <div class="tab-pane fade {{ $tab == 'payment_methods' ? 'active show' : '' }}" id="payment_methods" role="tabpanel">
											<div class="pd-20">
                                                 <div class="row">
                                                    <div class="col-md-4">
                                                        <h5>Imagen primer banner</h5><br>
                                                        
                                                        <form action="{{ route('admin.change-banner') }}" method="POST" enctype="multipart/form-data" id="change_site_logo_form">
                                                            @csrf
                                                            <div class="mb-2">
                                                                <input type="file" name="site_bannero" id="site_bannero" class="form-control">
                                                                <span class="text-danger error-text site_logo_error"></span>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Cambiar imagen</button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h5>Imagen segundo banner</h5><br>
                                                        
                                                        <form action="{{ route('admin.change-banner') }}" method="POST" enctype="multipart/form-data" id="change_site_logo_form">
                                                            @csrf
                                                            <div class="mb-2">
                                                                <input type="file" name="site_bannert" id="site_bannert" class="form-control">
                                                                <span class="text-danger error-text site_logo_error"></span>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Cambiar imagen</button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h5>Imagen tercer banner</h5><br>
                                                        
                                                        <form action="{{ route('admin.change-banner') }}" method="POST" enctype="multipart/form-data" id="change_site_logo_form">
                                                            @csrf
                                                            <div class="mb-2">
                                                                <input type="file" name="site_bannerth" id="site_bannerth" class="form-control">
                                                                <span class="text-danger error-text site_logo_error"></span>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Cambiar imagen</button>
                                                        </form>
                                                    </div>
                                                 </div>
                                                 
											</div> <br>
                                            <div class="pd-20">  <h5>Segunda sección banners</h5><br>
                                                 <div class="row">
                                               
                                                    <div class="col-md-6">
                                                        <h5>Banner Registrate</h5><br>
                                                        
                                                        <form action="{{ route('admin.change-banner') }}" method="POST" enctype="multipart/form-data" id="change_site_logo_form">
                                                            @csrf
                                                            <div class="mb-2">
                                                                <input type="file" name="site_bannerf" id="site_bannerf" class="form-control">
                                                                <span class="text-danger error-text site_logo_error"></span>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Cambiar imagen</button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h5>Banner Las mejores marcas</h5><br>
                                                        
                                                        <form action="{{ route('admin.change-banner') }}" method="POST" enctype="multipart/form-data" id="change_site_logo_form">
                                                            @csrf
                                                            <div class="mb-2">
                                                                <input type="file" name="site_bannerfiv" id="site_bannerfiv" class="form-control">
                                                                <span class="text-danger error-text site_logo_error"></span>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Cambiar imagen</button>
                                                        </form>
                                                    </div>
                                                  
                                                 </div>
                                                 
											</div>
										</div>
                                        
									</div>
								</div>
</div>
