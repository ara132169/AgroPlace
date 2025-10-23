
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
													<form wire:submit='updatePassword()'>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="">Contraseña actual</label>
                                                                    <input type="password" placeholder="Contraseña actual"
                                                                    wire:model="current_password" class="form-control">
                                                                    @error('current_password')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="">Nueva contraseña</label>
                                                                    <input type="password" placeholder="Nueva contraseña" wire:model='new_password' class="form-control">
                                                                    @error('new_password')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="">Confirma tu contraseña</label>
                                                                    <input type="password" placeholder="Confirma tu nueva contraseña" wire:model='new_password_confirmation' class="form-control">
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
										
										</div>
									</div>
								</div>
</div>




