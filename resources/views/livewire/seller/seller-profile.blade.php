<div>
<div class="row">
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
                            <div class="profile-photo">
                                <a href="javascript:;" onclick="event.preventDefault();document.getElementById('sellerProfilePictureFile').click();" class="edit-avatar">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <img src="{{ $seller->picture }}" alt="Profile Picture" class="avatar-photo" id="sellerProfilePicture">
                                <input type="file" name="sellerProfilePictureFile" id="sellerProfilePictureFile" class="d-none" style="opacity: 0">
                            </div>

								<h5 class="text-center h5 mb-0">{{$seller->name}}</h5>
								<p class="text-center text-muted font-14">
                                {{$seller->email}}
								</p>
								
								
							</div>
						</div>
						<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
							<div class="card-box height-100-p overflow-hidden">
								<div class="profile-tab height-100-p">
									<div class="tab height-100-p">
										<ul class="nav nav-tabs customtab" role="tablist">
											<li class="nav-item">
                                            <a wire:click.prevent='selectTab("personal_details")' class="nav-link {{ $tab == 'personal_details' ? 'active' : '' }}" data-toggle="tab" href="#personal_details" role="tab">Datos Personales</a>
											</li>
											<li class="nav-item">
                                            <a wire:click.prevent='selectTab("update_password")' class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab" href="#update_password" role="tab">Actualizar contraseña</a>
											</li>
											
										</ul>
										<div class="tab-content">
											<!-- Timeline Tab start -->
                                            <div class="tab-pane fade {{ $tab == 'personal_details' ? 'active show' : '' }}" id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit='updateSellerPersonalDetails()'>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Nombre completo</label>
                                                    <input type="text" class="form-control" placeholder="Ingresa tu nombre completo" wire:model.live='name'>
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Correo electrónico</label>
                                                    <input type="text" class="form-control" placeholder="Ingresa tu correo electrónico" wire:model.live='email' disabled>
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Nombre de usuario</label>
                                                    <input type="text" class="form-control" placeholder="Ingresa tu nombre de usuario" wire:model.live='username'>
                                                    @error('username')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Número de teléfono</label>
                                                    <input type="text" class="form-control" placeholder="Ingresa tu número de teléfono" wire:model.live='phone'>
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Ubicación</label>
                                                    <input type="text" class="form-control" placeholder="Ingresa la dirección de tu negocio" wire:model.live='address'>
                                                    @error('address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    </form>
                                </div>
                            </div>
											<!-- Timeline Tab End -->
											<!-- Tasks Tab start -->
											<div class="tab-pane fade {{ $tab == 'update_password' ? 'active show' : '' }}" id="update_password" role="tabpanel">
                                            <div class="pd-20 profile-task-wrap">
                                    <form wire:submit='updatePassword()'>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Contraseña actual</label>
                                                    <input type="password" class="form-control" placeholder="" wire:model='current_password'>
                                                    @error('current_password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Nueva contraseña</label>
                                                    <input type="password" class="form-control" placeholder="" wire:model='new_password'>
                                                    @error('new_password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Confirma tu nueva contraseña</label>
                                                    <input type="password" class="form-control" placeholder="" wire:model='new_password_confirmation'>
                                                    @error('new_password_confirmation')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
                                    </form>
                                </div>
											</div>
											<!-- Tasks Tab End -->
										
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
</div>
