
<div>
<div class="profile-tab height-100-p">
									<div class="tab height-100-p">
										<ul class="nav nav-tabs customtab" role="tablist">
											<li class="nav-item">
												<a wire:click.prevent='selectTab("personal_details")' class="nav-link {{$tab=='personal_details' ? 'active' : ''}}" 
                                                data-toggle="tab" href="#personal_details" role="tab">Información general</a>
											</li>
											<li class="nav-item">
												<a wire:click.prevent='selectTab("update_password")' class="nav-link {{$tab=='update_password' ? 'active' : ''}}" data-toggle="tab" href="#update_password" role="tab">Actualizar contraseña</a>
											</li>
										</ul>
										<div class="tab-content">
											<!-- Timeline Tab start -->
											<div class="tab-pane fade {{$tab=='personal_details' ? 'active show' : ''}}" id="personal_details" role="tabpanel">
												<div class="pd-20">
													<form wire:submit.prevent='updateAdminPersonalDetails()'>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form group">
                                                                    <label for=""> Nombre </label>
                                                                    <input type="text" class="form-control" wire:model='name'
                                                                    placeholder="Ingresa tu nombre completo">
                                                                    @error('name')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form group">
                                                                    <label for=""> Correo electrónico</label>
                                                                    <input type="text" class="form-control" wire:model='email'
                                                                    placeholder="Ingresa tu correo electrónico">
                                                                    @error('email')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form group">
                                                                    <label for=""> Nombre de usuario </label>
                                                                    <input type="text" class="form-control" wire:model='username'
                                                                    placeholder="Ingresa tu nombre completo">
                                                                    @error('username')
                                                                    <span class="text-danger">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div><br>
                                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="toast" >Guardar cambios</button>
                                                    </form>
												</div>
											</div>
											<!-- Timeline Tab End -->
											<!-- Tasks Tab start -->
											<div class="tab-pane fade {{$tab=='update_password' ? 'active show' : ''}}" id="update_password" role="tabpanel">
												<div class="pd-20 profile-task-wrap">
                                                    <div class="alert alert-info">
                                                        <i class="fa fa-info-circle"></i> 
                                                        <strong>Información de seguridad:</strong> Después de actualizar tu contraseña, recibirás un email de confirmación.
                                                    </div>
													<form wire:submit='updatePassword()'>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for=""><i class="fa fa-lock"></i> Contraseña actual *</label>
                                                                    <input type="password" placeholder="Contraseña actual"
                                                                    wire:model="current_password" class="form-control" required>
                                                                    @error('current_password')
                                                                    <span class="text-danger"><i class="fa fa-exclamation-triangle"></i> {{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for=""><i class="fa fa-key"></i> Nueva contraseña *</label>
                                                                    <input type="password" placeholder="Nueva contraseña (mín. 5 caracteres)" wire:model='new_password' class="form-control" required>
                                                                    @error('new_password')
                                                                        <span class="text-danger"><i class="fa fa-exclamation-triangle"></i> {{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for=""><i class="fa fa-check"></i> Confirma tu contraseña *</label>
                                                                    <input type="password" placeholder="Confirma tu nueva contraseña" wire:model='new_password_confirmation' class="form-control" required>
                                                                    @error('new_password_confirmation')
                                                                        <span class="text-danger"><i class="fa fa-exclamation-triangle"></i> {{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                                                <span wire:loading.remove>
                                                                    <i class="fa fa-save"></i> Actualizar contraseña
                                                                </span>
                                                                <span wire:loading>
                                                                    <i class="fa fa-spinner fa-spin"></i> Actualizando...
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </form>
												</div>
											</div>
										
										</div>
									</div>
								</div>
</div>




