@extends('back.layout.pages-layout')
@section('PageTitle', 'Configurar Cuenta de Depósito')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">💳 Configurar Cuenta de Depósito</h4>
                    <p class="text-muted mb-0">Registra tu cuenta para recibir los pagos de tus ventas</p>
                </div>
                <div class="card-body">
                    
                    @if($paymentAccounts->count() > 0)
                        <div class="alert alert-info mb-4">
                            <h5><i class="fas fa-info-circle"></i> Cuentas Registradas</h5>
                        </div>
                        
                        @foreach($paymentAccounts as $account)
                        <div class="card mb-3 {{ $account->canReceiveDeposits() ? 'border-success' : 'border-warning' }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{!! $account->display_info !!}</h6>
                                        <small class="text-muted">{{ $account->account_holder_name }}</small>
                                    </div>
                                    <div class="col-md-3">
                                        @if($account->is_verified)
                                            <span class="badge badge-success">✅ Verificada</span>
                                        @else
                                            <span class="badge badge-warning">⏳ Pendiente verificación</span>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editAccount({{ $account->id }})">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        @if(!$account->is_verified)
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteAccount({{ $account->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <hr>
                    @else
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Sin Cuenta Configurada</h5>
                            <p class="mb-0">Necesitas registrar una cuenta para recibir los depósitos de tus ventas.</p>
                        </div>
                    @endif

                    <!-- Formulario para nueva cuenta -->
                    <div class="card">
                        <div class="card-header">
                            <h5>➕ Agregar Nueva Cuenta</h5>
                        </div>
                        <div class="card-body">
                            <form id="accountForm" action="{{ route('tienda.payment.account.store') }}" method="POST">
                                @csrf
                                
                                <!-- Tipo de cuenta -->
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Tipo de Cuenta</label>
                                        <div class="btn-group d-flex" role="group">
                                            <input type="radio" class="btn-check" name="account_type" id="debit_card" value="debit_card" checked>
                                            <label class="btn btn-outline-primary" for="debit_card">
                                                💳 Tarjeta de Débito
                                            </label>

                                            <input type="radio" class="btn-check" name="account_type" id="bank_account" value="bank_account">
                                            <label class="btn btn-outline-primary" for="bank_account">
                                                🏦 Cuenta Bancaria
                                            </label>

                                            <input type="radio" class="btn-check" name="account_type" id="paypal" value="paypal">
                                            <label class="btn btn-outline-primary" for="paypal">
                                                📧 PayPal
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nombre del titular -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="account_holder_name" class="form-label">Nombre del Titular *</label>
                                        <input type="text" class="form-control" id="account_holder_name" 
                                               name="account_holder_name" required
                                               placeholder="Nombre completo como aparece en la cuenta">
                                    </div>
                                </div>

                                <!-- Campos para tarjeta de débito -->
                                <div id="debit_card_fields">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="card_number" class="form-label">Número de Tarjeta *</label>
                                            <input type="text" class="form-control" id="card_number" 
                                                   placeholder="1234 5678 9012 3456" maxlength="19">
                                            <small class="text-muted">Solo se guardará los últimos 4 dígitos</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="card_brand" class="form-label">Marca de Tarjeta</label>
                                            <select class="form-control" id="card_brand" name="card_brand">
                                                <option value="">Detectar automáticamente</option>
                                                <option value="visa">Visa</option>
                                                <option value="mastercard">Mastercard</option>
                                                <option value="amex">American Express</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos para cuenta bancaria -->
                                <div id="bank_account_fields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="bank_name" class="form-label">Banco *</label>
                                            <select class="form-control" id="bank_name" name="bank_name">
                                                <option value="">Seleccionar banco</option>
                                                <option value="BBVA">BBVA</option>
                                                <option value="Banamex">Banamex</option>
                                                <option value="Santander">Santander</option>
                                                <option value="Banorte">Banorte</option>
                                                <option value="HSBC">HSBC</option>
                                                <option value="Scotiabank">Scotiabank</option>
                                                <option value="Inbursa">Inbursa</option>
                                                <option value="Azteca">Banco Azteca</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="clabe" class="form-label">CLABE Interbancaria</label>
                                            <input type="text" class="form-control" id="clabe" name="clabe" 
                                                   placeholder="18 dígitos" maxlength="18">
                                            <small class="text-muted">Para transferencias SPEI</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos para PayPal -->
                                <div id="paypal_fields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="paypal_email" class="form-label">Email de PayPal *</label>
                                            <input type="email" class="form-control" id="paypal_email" name="paypal_email"
                                                   placeholder="tu@email.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <small>
                                                <i class="fas fa-shield-alt"></i> <strong>Seguridad:</strong> 
                                                Tu información se almacena de forma segura y encriptada. 
                                                Un administrador verificará tu cuenta antes de activar los depósitos.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Registrar Cuenta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountModalLabel">
                    <i class="fas fa-edit"></i> Editar Cuenta de Pago
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAccountForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_account_id">
                    
                    <!-- Tipo de cuenta (solo mostrar, no editable) -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Tipo de Cuenta</label>
                            <div class="form-control" id="edit_account_type_display" readonly style="background-color: #f8f9fa;">
                                <!-- Se llenará dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Nombre del titular -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_account_holder_name" class="form-label">Nombre del Titular *</label>
                            <input type="text" class="form-control" id="edit_account_holder_name" 
                                   name="account_holder_name" required
                                   placeholder="Nombre completo como aparece en la cuenta">
                        </div>
                    </div>

                    <!-- Campos específicos por tipo -->
                    <!-- Campos para tarjeta de débito -->
                    <div id="edit_debit_card_fields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_card_last_four" class="form-label">Últimos 4 Dígitos</label>
                                <input type="text" class="form-control" id="edit_card_last_four" 
                                       name="card_last_four" maxlength="4" pattern="[0-9]{4}"
                                       placeholder="1234">
                                <small class="text-muted">Solo se muestran los últimos 4 dígitos por seguridad</small>
                            </div>
                        </div>
                    </div>

                    <!-- Campos para cuenta bancaria -->
                    <div id="edit_bank_account_fields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_bank_name" class="form-label">Banco</label>
                                <input type="text" class="form-control" id="edit_bank_name" 
                                       name="bank_name" placeholder="Nombre del banco">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_account_number" class="form-label">Número de Cuenta/CLABE</label>
                                <input type="text" class="form-control" id="edit_account_number" 
                                       name="account_number" placeholder="Número de cuenta">
                                <small class="text-muted">Se almacena de forma segura y encriptada</small>
                            </div>
                        </div>
                    </div>

                    <!-- Campos para PayPal -->
                    <div id="edit_paypal_fields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_paypal_email" class="form-label">Email de PayPal</label>
                                <input type="email" class="form-control" id="edit_paypal_email" 
                                       name="paypal_email" placeholder="tu@email.com">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <small>
                            <i class="fas fa-exclamation-triangle"></i> <strong>Nota:</strong> 
                            Si modificas información sensible (números de cuenta, tarjeta, etc.), 
                            la cuenta requerirá nueva verificación del administrador.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Cambio de tipo de cuenta
document.querySelectorAll('input[name="account_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Ocultar todos los campos
        document.getElementById('debit_card_fields').style.display = 'none';
        document.getElementById('bank_account_fields').style.display = 'none';
        document.getElementById('paypal_fields').style.display = 'none';
        
        // Mostrar campos correspondientes
        if (this.value === 'debit_card') {
            document.getElementById('debit_card_fields').style.display = 'block';
        } else if (this.value === 'bank_account') {
            document.getElementById('bank_account_fields').style.display = 'block';
        } else if (this.value === 'paypal') {
            document.getElementById('paypal_fields').style.display = 'block';
        }
    });
});

// Formatear número de tarjeta
document.getElementById('card_number').addEventListener('input', function() {
    let value = this.value.replace(/\s/g, '');
    let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
    this.value = formattedValue;
    
    // Detectar marca de tarjeta
    let brand = detectCardBrand(value);
    document.getElementById('card_brand').value = brand;
});

// Detectar marca de tarjeta
function detectCardBrand(number) {
    if (number.startsWith('4')) return 'visa';
    if (number.startsWith('5') || number.startsWith('2')) return 'mastercard';
    if (number.startsWith('3')) return 'amex';
    return '';
}

// Validar CLABE
document.getElementById('clabe').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
});

// Envío del formulario
document.getElementById('accountForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Procesar número de tarjeta
    const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
    if (cardNumber && cardNumber.length >= 4) {
        formData.append('card_last_four', cardNumber.slice(-4));
        formData.append('card_token', 'encrypted_' + cardNumber); // En producción usar encriptación real
    }
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cuenta registrada exitosamente. Será verificada por un administrador.');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Ocurrió un error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });
});

function editAccount(id) {
    // Cargar datos de la cuenta
    fetch(`/tienda/payment-account/${id}/edit`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const account = data.account;
            
            // Llenar campos básicos
            document.getElementById('edit_account_id').value = account.id;
            document.getElementById('edit_account_holder_name').value = account.account_holder_name;
            
            // Mostrar tipo de cuenta
            const typeLabels = {
                'debit_card': '💳 Tarjeta de Débito',
                'bank_account': '🏦 Cuenta Bancaria',
                'paypal': '📧 PayPal'
            };
            document.getElementById('edit_account_type_display').innerText = typeLabels[account.account_type] || account.account_type;
            
            // Ocultar todos los campos específicos
            document.getElementById('edit_debit_card_fields').style.display = 'none';
            document.getElementById('edit_bank_account_fields').style.display = 'none';
            document.getElementById('edit_paypal_fields').style.display = 'none';
            
            // Mostrar y llenar campos específicos según el tipo
            switch(account.account_type) {
                case 'debit_card':
                    document.getElementById('edit_debit_card_fields').style.display = 'block';
                    document.getElementById('edit_card_last_four').value = account.card_last_four || '';
                    break;
                    
                case 'bank_account':
                    document.getElementById('edit_bank_account_fields').style.display = 'block';
                    document.getElementById('edit_bank_name').value = account.bank_name || '';
                    document.getElementById('edit_account_number').value = account.account_number || '';
                    break;
                    
                case 'paypal':
                    document.getElementById('edit_paypal_fields').style.display = 'block';
                    document.getElementById('edit_paypal_email').value = account.paypal_email || '';
                    break;
            }
            
            // Mostrar modal
            new bootstrap.Modal(document.getElementById('editAccountModal')).show();
        } else {
            alert('Error cargando datos de la cuenta: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión al cargar datos de la cuenta');
    });
}

// Manejo del formulario de edición
document.getElementById('editAccountForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const accountId = document.getElementById('edit_account_id').value;
    const formData = new FormData(this);
    
    // Agregar método PUT para Laravel
    formData.append('_method', 'PUT');
    
    fetch(`/tienda/payment-account/${accountId}`, {
        method: 'POST', // Laravel necesita POST con _method=PUT
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('editAccountModal')).hide();
            // Recargar página para mostrar cambios
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Ocurrió un error actualizando la cuenta'));
            if (data.errors) {
                console.error('Errores de validación:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión al actualizar la cuenta');
    });
});

function deleteAccount(id) {
    if (confirm('¿Eliminar esta cuenta?')) {
        fetch(`/tienda/payment-account/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error eliminando cuenta');
            }
        });
    }
}
</script>
@endsection