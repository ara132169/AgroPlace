// Variables globales para el manejo de depósitos
let currentDepositId = null;

// Función principal para procesar depósitos
function processDeposit(depositId) {
    console.log('🏦 processDeposit llamada con ID:', depositId);
    currentDepositId = depositId;
    
    const url = `/admin/depositos/${depositId}/detalles`;
    console.log('📡 Obteniendo detalles desde:', url);
    
    fetch(url)
        .then(response => {
            console.log('📥 Respuesta recibida:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('📊 Datos recibidos:', data);
            if (data.success) {
                // Mostrar información del depósito
                const depositDetailsEl = document.getElementById('depositDetails');
                if (depositDetailsEl) {
                    depositDetailsEl.innerHTML = `
                        <h6>📋 Información del Depósito</h6>
                        <p><strong>Vendedor:</strong> ${data.deposit.seller.name} (${data.deposit.seller.email})</p>
                        <p><strong>Monto:</strong> <span class="text-success">$${data.deposit.amount}</span></p>
                        <p><strong>Cuenta actual:</strong> ${data.deposit.payment_account.display_info}</p>
                        <p><strong>Referencia:</strong> ${data.deposit.reference}</p>
                    `;
                }
                
                // Cargar cuentas del vendedor
                const accountSelect = document.getElementById('destinationAccount');
                if (accountSelect) {
                    accountSelect.innerHTML = '<option value="">Seleccionar cuenta destino...</option>';
                    
                    data.seller_accounts.forEach(account => {
                        const option = document.createElement('option');
                        option.value = account.id;
                        option.selected = account.id === data.deposit.payment_account_id;
                        option.textContent = `${account.display_info} - ${account.account_holder_name}`;
                        if (!account.is_verified) {
                            option.textContent += ' ⚠️ (No verificada)';
                            option.disabled = true;
                        }
                        accountSelect.appendChild(option);
                    });
                }
                
                // Mostrar modal (compatible con Bootstrap 4 y 5)
                const modal = document.getElementById('processModal');
                if (modal) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        try {
                            const bsModal = new bootstrap.Modal(modal);
                            bsModal.show();
                        } catch (error) {
                            console.log('🔄 Bootstrap 5 falló, intentando jQuery...');
                            fallbackToJquery();
                        }
                    } else {
                        fallbackToJquery();
                    }
                    
                    function fallbackToJquery() {
                        if (typeof $ !== 'undefined' && $.fn.modal) {
                            $('#processModal').modal('show');
                        } else {
                            console.error('❌ No se puede mostrar el modal: ni Bootstrap ni jQuery disponibles');
                            alert('⚠️ Modal no disponible. Revisa los datos en la consola.');
                        }
                    }
                } else {
                    console.error('❌ No se encontró el modal processModal');
                    alert('⚠️ Modal no encontrado');
                }
            } else {
                console.error('❌ Error en respuesta:', data);
                alert('❌ Error: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('❌ Error en fetch:', error);
            alert('❌ Error de conexión: ' + error.message);
        });
}

// Función alternativa para completar depósito (sin dependencias externas)
function completeDepositDirect(depositId) {
    console.log('⚡ completeDepositDirect llamada con ID:', depositId);
    
    // Confirmación simple
    const confirmed = confirm(
        `¿Completar el depósito ${depositId}?\n\n` +
        'Esta acción marcará el depósito como completado.\n' +
        '¿Está seguro?'
    );
    
    if (!confirmed) {
        console.log('❌ Usuario canceló la operación');
        return;
    }
    
    console.log('✅ Usuario confirmó. Procediendo...');
    
    // Obtener token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('❌ Token CSRF no encontrado');
        alert('❌ Error de seguridad: No se puede proceder sin token CSRF');
        return;
    }
    
    console.log('🔑 Token CSRF obtenido:', csrfToken.substring(0, 10) + '...');
    
    // Preparar datos
    const requestData = {
        status: 'completed',
        admin_notes: `Completado directamente por administrador el ${new Date().toLocaleString()}`
    };
    
    console.log('📤 Enviando datos:', requestData);
    
    // Realizar petición
    const url = `/admin/depositos/${depositId}/actualizar-estado`;
    console.log('🌐 URL destino:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('📡 Respuesta recibida:', {
            status: response.status,
            statusText: response.statusText,
            ok: response.ok,
            headers: [...response.headers.entries()]
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('📊 Datos procesados:', data);
        
        if (data.success) {
            alert(`✅ ${data.message}`);
            console.log('🔄 Recargando página...');
            setTimeout(() => location.reload(), 1500);
        } else {
            console.error('❌ Error del servidor:', data);
            alert(`❌ Error: ${data.message || 'Error desconocido'}`);
        }
    })
    .catch(error => {
        console.error('❌ Error en la petición:', error);
        alert(`❌ Error de conexión: ${error.message}`);
    });
}

// Función para completar depósito directamente (sin modal)
function completeDeposit(depositId) {
    console.log('✅ completeDeposit llamada con ID:', depositId);
    
    // Usar la función directa para evitar problemas con modales
    completeDepositDirect(depositId);
}

// Función para fallar depósito
function failDeposit(depositId) {
    console.log('❌ failDeposit llamada con ID:', depositId);
    const reason = prompt('Motivo del fallo:');
    if (reason && reason.trim() !== '') {
        updateDepositStatus(depositId, 'failed', { failure_reason: reason });
    }
}

// Función para actualizar estado del depósito
function updateDepositStatus(depositId, status, extraData = {}, buttonElement = null, originalButtonText = '') {
    console.log('🔄 Actualizando depósito:', { depositId, status, extraData });
    
    // Recopilar datos del formulario
    const data = {
        status: status,
        destination_account_id: getElementValue('destinationAccount'),
        deposit_method: getElementValue('depositMethod'),
        transaction_id: getElementValue('transactionId'),
        admin_notes: getElementValue('adminNotes'),
        ...extraData
    };
    
    console.log('📤 Datos a enviar:', data);
    
    const url = `/admin/depositos/${depositId}/actualizar-estado`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                      document.querySelector('input[name="_token"]')?.value;
    
    if (!csrfToken) {
        console.error('❌ No se encontró el token CSRF');
        alert('❌ Error de seguridad: Token CSRF no encontrado');
        resetButton();
        return;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('📥 Respuesta del servidor:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('📊 Respuesta procesada:', data);
        if (data.success) {
            // Cerrar modal si está abierto
            closeModal('processModal');
            
            // Mostrar mensaje de éxito
            alert('✅ ' + data.message);
            
            // Recargar la página para ver los cambios
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            console.error('❌ Error en respuesta:', data.message);
            alert('❌ Error: ' + data.message);
            resetButton();
        }
    })
    .catch(error => {
        console.error('❌ Error en actualización:', error);
        alert('❌ Error de conexión: ' + error.message);
        resetButton();
    });
    
    function resetButton() {
        if (buttonElement && originalButtonText) {
            buttonElement.innerHTML = originalButtonText;
            buttonElement.disabled = false;
        }
    }
}

// Funciones del modal
function submitProcess() {
    console.log('📝 submitProcess llamada');
    updateDepositStatus(currentDepositId, 'processing');
}

function submitComplete() {
    console.log('✅ submitComplete llamada');
    updateDepositStatus(currentDepositId, 'completed');
}

// Función para filtrar depósitos
function filterDeposits() {
    const status = getElementValue('statusFilter');
    const seller = getElementValue('sellerFilter');
    
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (seller) params.append('seller', seller);
    
    window.location.href = window.location.pathname + '?' + params.toString();
}

// Función para limpiar filtros
function clearFilters() {
    window.location.href = window.location.pathname;
}

// Función de test de conexión
function testConnection() {
    console.log('🧪 Probando conexión...');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        alert('❌ Token CSRF no encontrado');
        return;
    }
    
    fetch('/admin/depositos/test-update/999', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            status: 'test',
            test_data: 'Prueba de conexión'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ Test exitoso:', data);
        alert('🧪 Test exitoso: ' + data.message);
    })
    .catch(error => {
        console.error('❌ Error en test:', error);
        alert('❌ Test falló: ' + error.message);
    });
}

// Función SEGURA para procesar todos los depósitos pendientes
function safeProcessAllPending() {
    // Primera confirmación
    const firstConfirm = confirm(
        '⚠️ ACCIÓN PELIGROSA ⚠️\n\n' +
        'Está a punto de procesar TODOS los depósitos pendientes.\n' +
        'Esto afectará múltiples transacciones.\n\n' +
        '¿Está completamente seguro de que quiere continuar?'
    );
    
    if (!firstConfirm) {
        console.log('❌ Usuario canceló en primera confirmación');
        return;
    }
    
    // Segunda confirmación más específica
    const pendingCount = document.querySelectorAll('tr[data-status="pending"]').length;
    const secondConfirm = confirm(
        `🔍 CONFIRMACIÓN FINAL\n\n` +
        `Se van a procesar aproximadamente ${pendingCount} depósito(s) pendiente(s).\n` +
        `Esto los cambiará de "PENDIENTE" a "EN PROCESO".\n\n` +
        `¿Proceder con la acción?`
    );
    
    if (!secondConfirm) {
        console.log('❌ Usuario canceló en segunda confirmación');
        return;
    }
    
    console.log('✅ Usuario confirmó procesamiento en lote');
    
    // Ejecutar la función original pero con más logging
    processAllPending();
}

// Función para procesar todos los depósitos pendientes (ORIGINAL)
function processAllPending() {
    console.log('🚨 EJECUTANDO PROCESAMIENTO EN LOTE DE DEPÓSITOS PENDIENTES');
    console.warn('⚠️ Esta función afecta múltiples depósitos simultáneamente');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        alert('❌ Error de seguridad: Token CSRF no encontrado');
        return;
    }
    
    console.log('📤 Enviando petición de procesamiento en lote...');
    
    fetch('/admin/depositos/procesar-todos-pendientes', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'bulk_process_pending',
            timestamp: new Date().toISOString(),
            admin_action: true
        })
    })
    .then(response => {
        console.log('📥 Respuesta del servidor:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('📊 Resultado del procesamiento en lote:', data);
        
        if (data.success) {
            alert(`✅ ${data.message}\n\nLa página se recargará para mostrar los cambios.`);
            setTimeout(() => location.reload(), 2000);
        } else {
            alert(`❌ Error en procesamiento en lote: ${data.message}`);
        }
    })
    .catch(error => {
        console.error('❌ Error en procesamiento en lote:', error);
        alert('❌ Error de conexión durante el procesamiento en lote');
    });
}

// Función para mostrar logs recientes
function showRecentLogs() {
    // Por ahora mostrar información del estado actual
    console.log('📋 ESTADO ACTUAL DEL SISTEMA:');
    
    // Contar depósitos por estado
    const deposits = {
        pending: document.querySelectorAll('tr[data-status="pending"]').length,
        processing: document.querySelectorAll('tr[data-status="processing"]').length,
        completed: document.querySelectorAll('tr[data-status="completed"]').length,
        failed: document.querySelectorAll('tr[data-status="failed"]').length
    };
    
    console.table(deposits);
    
    // Información del sistema
    const systemInfo = {
        'Funciones JS cargadas': typeof window.completeDeposit === 'function',
        'CSRF Token': !!document.querySelector('meta[name="csrf-token"]'),
        'jQuery': typeof $ !== 'undefined',
        'Bootstrap': typeof bootstrap !== 'undefined',
        'URL actual': window.location.href,
        'Timestamp': new Date().toLocaleString()
    };
    
    console.table(systemInfo);
    
    alert(`📊 ESTADO DEL SISTEMA:\n\n` +
          `Depósitos Pendientes: ${deposits.pending}\n` +
          `Depósitos en Proceso: ${deposits.processing}\n` +
          `Depósitos Completados: ${deposits.completed}\n` +
          `Depósitos Fallidos: ${deposits.failed}\n\n` +
          `Ver consola para más detalles.`);
}

// Función para mostrar estadísticas
function showStats() {
    alert('Modal de estadísticas detalladas - En desarrollo');
}

// Función para ver detalles
function viewDetails(depositId) {
    alert('Ver detalles del depósito ' + depositId + ' - En desarrollo');
}

// FUNCIONES AUXILIARES

// Función auxiliar para obtener valor de elemento
function getElementValue(elementId) {
    const element = document.getElementById(elementId);
    return element ? element.value : null;
}

// Función auxiliar para cerrar modal (compatible con Bootstrap 4 y 5)
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // Intentar Bootstrap 5 primero
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            try {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                } else {
                    // Si no hay instancia, crear una nueva y cerrarla
                    const newModal = new bootstrap.Modal(modal);
                    newModal.hide();
                }
            } catch (error) {
                console.log('🔄 Bootstrap 5 falló, intentando jQuery...');
                fallbackToJquery();
            }
        } else {
            fallbackToJquery();
        }
        
        function fallbackToJquery() {
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#' + modalId).modal('hide');
            } else {
                console.error('❌ No se puede cerrar el modal: ni Bootstrap ni jQuery disponibles');
                // Fallback manual
                modal.style.display = 'none';
                modal.classList.remove('show');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }
        }
    }
}

// INICIALIZACIÓN

// Verificar que el DOM esté cargado
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAdminDeposits);
} else {
    initializeAdminDeposits();
}

function initializeAdminDeposits() {
    console.log('🚀 Admin Deposits JavaScript iniciado');
    console.log('📋 Funciones disponibles:', {
        processDeposit: typeof processDeposit,
        completeDeposit: typeof completeDeposit,
        failDeposit: typeof failDeposit,
        testConnection: typeof testConnection,
        filterDeposits: typeof filterDeposits,
        clearFilters: typeof clearFilters
    });
    
    // Verificar elementos del DOM críticos
    const criticalElements = ['depositDetails', 'destinationAccount', 'processModal'];
    criticalElements.forEach(elementId => {
        const element = document.getElementById(elementId);
        console.log(`📍 Elemento ${elementId}:`, element ? '✅ Encontrado' : '❌ No encontrado');
    });
    
    // Verificar Bootstrap y jQuery
    console.log('🔧 Librerías disponibles:', {
        Bootstrap: typeof bootstrap !== 'undefined' ? '✅ Disponible' : '❌ No disponible',
        jQuery: typeof $ !== 'undefined' ? '✅ Disponible' : '❌ No disponible',
        BootstrapVersion: typeof bootstrap !== 'undefined' && bootstrap.Modal ? 'v5+' : 'v4 o anterior'
    });
    
    // INTERCEPTOR DE PETICIONES AJAX PARA DETECTAR LLAMADAS AUTOMÁTICAS
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const url = args[0];
        const options = args[1] || {};
        
        // Log todas las peticiones relacionadas con depósitos
        if (typeof url === 'string' && url.includes('/admin/depositos/')) {
            console.warn('🌐 PETICIÓN AJAX DETECTADA:', {
                url: url,
                method: options.method || 'GET',
                stack: new Error().stack.split('\n').slice(1, 5),
                timestamp: new Date().toISOString()
            });
            
            // Alerta especial para procesamiento en lote
            if (url.includes('procesar-todos-pendientes')) {
                console.error('🚨 PROCESAMIENTO EN LOTE DETECTADO!');
                console.error('📍 Stack trace:', new Error().stack);
            }
        }
        
        return originalFetch.apply(this, args);
    };
    
    // Añadir funciones globales al window para debugging
    window.debugDepositSystem = {
        processDeposit,
        completeDeposit,
        completeDepositDirect,
        failDeposit,
        testConnection,
        updateDepositStatus
    };
    
    // Función de test completa
    window.testDepositSystem = function() {
        console.log('🧪 Ejecutando test completo del sistema de depósitos...');
        
        const tests = [
            {
                name: 'Verificar funciones principales',
                test: () => {
                    const functions = ['processDeposit', 'completeDeposit', 'failDeposit', 'testConnection'];
                    const results = functions.map(fn => ({
                        function: fn,
                        available: typeof window[fn] === 'function'
                    }));
                    console.table(results);
                    return results.every(r => r.available);
                }
            },
            {
                name: 'Verificar elementos DOM',
                test: () => {
                    const elements = ['processModal', 'depositDetails', 'destinationAccount'];
                    const results = elements.map(id => ({
                        element: id,
                        found: !!document.getElementById(id)
                    }));
                    console.table(results);
                    return true; // No crítico
                }
            },
            {
                name: 'Verificar CSRF token',
                test: () => {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    console.log('🔑 CSRF Token:', token ? 'Encontrado' : 'No encontrado');
                    return !!token;
                }
            },
            {
                name: 'Verificar librerías',
                test: () => {
                    const libs = {
                        jQuery: typeof $ !== 'undefined',
                        Bootstrap: typeof bootstrap !== 'undefined'
                    };
                    console.table(libs);
                    return true; // No crítico
                }
            }
        ];
        
        const results = tests.map(test => ({
            ...test,
            passed: test.test()
        }));
        
        console.log('📊 Resultados del test:');
        console.table(results);
        
        const allPassed = results.every(r => r.passed);
        console.log(allPassed ? '✅ Todos los tests pasaron' : '⚠️ Algunos tests fallaron');
        
        return results;
    };
    
    console.log('🔧 Para probar la conexión, ejecuta: testConnection()');
    console.log('🐛 Para debugging, usa: window.debugDepositSystem');
    console.log('🧪 Para test completo, usa: window.testDepositSystem()');
    console.log('🚨 INTERCEPTOR AJAX ACTIVO - Se loguearán todas las peticiones a /admin/depositos/');
}