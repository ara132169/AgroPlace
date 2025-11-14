# 🔄 DIAGRAMA DE FLUJO VISUAL: PROCESO COMPLETO DE COMPRA AGROMARKET

## 📊 FLUJO PRINCIPAL DEL SISTEMA

```mermaid
flowchart TD
    %% INICIO DEL PROCESO
    A[👤 Cliente navega productos] --> B{¿Producto de interés?}
    B -->|No| A
    B -->|Sí| C[Ver detalles del producto]
    C --> D{¿Agregar al carrito?}
    D -->|No| A
    D -->|Sí| E[Añadir al carrito]
    
    %% GESTIÓN DEL CARRITO
    E --> F[Ver carrito actualizado]
    F --> G{¿Seguir comprando?}
    G -->|Sí| A
    G -->|No| H[Proceder al checkout]
    
    %% PROCESO DE CHECKOUT
    H --> I[Completar datos de envío]
    I --> J[Seleccionar método de pago]
    J --> K[Procesar pago con Stripe]
    
    %% VALIDACIÓN DE PAGO
    K --> L{¿Pago exitoso?}
    L -->|No| M[Mostrar error de pago]
    M --> N{¿Reintentar?}
    N -->|Sí| J
    N -->|No| O[Abandonar compra]
    
    %% PAGO EXITOSO
    L -->|Sí| P[Crear orden en base de datos]
    P --> Q[Calcular comisiones automáticamente]
    Q --> R[Enviar email confirmación a cliente]
    R --> S[Notificar a vendedores]
    
    %% DASHBOARD UPDATES
    S --> T[Actualizar dashboard cliente]
    T --> U[Actualizar dashboard vendedores]
    U --> V[Registrar venta en panel admin]
    
    %% PROCESO ADMINISTRATIVO
    V --> W[Admin ve nueva comisión]
    W --> X{¿Procesar depósito?}
    X -->|No| Y[Mantener pendiente]
    Y --> Z[Esperar procesamiento manual]
    Z --> X
    
    %% DEPÓSITO MANUAL
    X -->|Sí| AA[Verificar cuenta de vendedor]
    AA --> BB{¿Cuenta verificada?}
    BB -->|No| CC[Solicitar verificación]
    CC --> DD[Vendedor actualiza datos]
    DD --> AA
    
    BB -->|Sí| EE[Calcular monto 85%]
    EE --> FF[Realizar transferencia bancaria]
    FF --> GG[Actualizar estado a 'Completado']
    GG --> HH[Notificar vendedor por email]
    HH --> II[✅ Proceso completado]
    
    %% ESTILOS
    classDef cliente fill:#e1f5fe
    classDef vendedor fill:#e8f5e8
    classDef admin fill:#fff3e0
    classDef pago fill:#fce4ec
    classDef decision fill:#f3e5f5
    
    class A,C,E,F,H,I,T cliente
    class S,U,DD,HH vendedor
    class V,W,AA,EE,FF,GG admin
    class J,K,L,P,Q pago
    class B,D,G,L,N,X,BB decision
```

## 🏪 FLUJO ESPECÍFICO DEL VENDEDOR

```mermaid
flowchart TD
    %% INICIO VENDEDOR
    A[🏪 Vendedor ingresa al sistema] --> B[Dashboard principal]
    B --> C{¿Qué acción realizar?}
    
    %% GESTIÓN DE VENTAS
    C -->|Ver ventas| D[Lista de órdenes]
    D --> E[Seleccionar orden específica]
    E --> F[Ver detalle completo]
    F --> G[👤 Info cliente<br/>🛍️ Productos<br/>💰 Comisión 15%<br/>💵 Ganancia 85%]
    G --> H{¿Actualizar estado?}
    H -->|Sí| I[Cambiar a 'Preparando'/'Enviado']
    H -->|No| J[Volver a lista]
    I --> J
    J --> C
    
    %% GESTIÓN DE CUENTAS
    C -->|Gestionar pagos| K[Mis cuentas de pago]
    K --> L{¿Tiene cuentas?}
    L -->|No| M[Agregar nueva cuenta]
    L -->|Sí| N[Ver cuentas existentes]
    
    M --> O[Completar datos bancarios]
    O --> P[📋 Banco<br/>👤 Titular<br/>🔢 Número cuenta<br/>🏛️ CLABE<br/>📄 Comprobantes]
    P --> Q[Enviar para verificación]
    Q --> R[⏳ Esperando aprobación admin]
    R --> S{¿Cuenta aprobada?}
    S -->|No| T[📧 Recibir feedback<br/>🔄 Corregir datos]
    T --> O
    S -->|Sí| U[✅ Cuenta activa]
    U --> N
    
    N --> V[Seleccionar cuenta]
    V --> W{¿Qué hacer?}
    W -->|Editar| X[Modificar datos]
    W -->|Eliminar| Y[Confirmar eliminación]
    W -->|Ver historial| Z[Historial de depósitos]
    X --> Q
    Y --> AA[Cuenta eliminada]
    AA --> K
    
    %% HISTORIAL Y BALANCE
    C -->|Ver balance| Z
    Z --> BB[📊 Balance pendiente<br/>💰 Total ganado<br/>🏦 Depósitos recibidos<br/>📅 Fechas de transacciones]
    BB --> CC{¿Solicitar depósito?}
    CC -->|Sí| DD{¿Cuenta activa disponible?}
    DD -->|No| EE[Configurar cuenta primero]
    EE --> K
    DD -->|Sí| FF[Solicitar depósito al admin]
    FF --> GG[📤 Solicitud enviada<br/>⏰ Tiempo estimado: 24-48h]
    GG --> HH[Esperar procesamiento]
    CC -->|No| C
    HH --> C
    
    %% ESTILOS
    classDef vendedor fill:#e8f5e8
    classDef cuenta fill:#e3f2fd
    classDef balance fill:#f1f8e9
    classDef decision fill:#f3e5f5
    
    class A,B,D,E,F,G,I,J vendedor
    class K,L,M,N,O,P,Q,U,V,X,Y,AA cuenta
    class Z,BB,FF,GG,HH balance
    class C,H,L,S,W,CC,DD decision
```

## ⚡ FLUJO ESPECÍFICO DEL ADMINISTRADOR

```mermaid
flowchart TD
    %% INICIO ADMIN
    A[⚡ Admin ingresa al sistema] --> B[Dashboard principal]
    B --> C[📊 Métricas globales<br/>💰 Ingresos del día<br/>📈 Comisiones generadas<br/>🏪 Vendedores activos<br/>👥 Nuevos clientes]
    C --> D{¿Qué gestionar?}
    
    %% GESTIÓN DE VENTAS
    D -->|Ver ventas| E[Lista global de órdenes]
    E --> F[🔍 Filtros:<br/>📅 Por fecha<br/>🏪 Por vendedor<br/>👤 Por cliente<br/>💰 Por monto]
    F --> G[Seleccionar orden]
    G --> H[Ver detalle completo]
    H --> I[👤 Cliente: datos contacto<br/>🏪 Vendedor: productos<br/>💰 Total venta<br/>💸 Comisión plataforma 15%<br/>💵 Monto para vendedor 85%<br/>🔄 Estado depósito]
    I --> J{¿Acción requerida?}
    J -->|No| K[Volver a lista]
    J -->|Sí| L[Procesar según necesidad]
    L --> K
    K --> D
    
    %% GESTIÓN DE DEPÓSITOS
    D -->|Gestionar depósitos| M[Panel de depósitos pendientes]
    M --> N[📋 Lista vendedores con balance]
    N --> O[Seleccionar vendedor]
    O --> P[Ver información detallada]
    P --> Q[🏪 Vendedor: nombre, email<br/>💰 Balance pendiente<br/>📅 Ventas incluidas<br/>🏦 Cuenta destino<br/>📄 Documentos verificación]
    Q --> R{¿Cuenta verificada?}
    
    %% VERIFICACIÓN DE CUENTA
    R -->|No| S[Revisar documentos]
    S --> T{¿Documentos válidos?}
    T -->|No| U[📧 Solicitar correcciones]
    U --> V[Notificar vendedor]
    V --> W[Esperar nueva documentación]
    W --> S
    T -->|Sí| X[✅ Aprobar cuenta]
    X --> Y[Marcar como verificada]
    Y --> Z[📧 Notificar aprobación]
    Z --> R
    
    %% PROCESO DE DEPÓSITO
    R -->|Sí| AA[Calcular monto final]
    AA --> BB[💵 Balance total<br/>💸 Menos comisión 15%<br/>💰 Monto neto 85%]
    BB --> CC[Abrir banca en línea]
    CC --> DD[Crear transferencia SPEI]
    DD --> EE[📝 Datos:<br/>🏦 Banco destino<br/>👤 Beneficiario<br/>🔢 Cuenta/CLABE<br/>💰 Monto calculado<br/>📋 Concepto: Pago AgroMarket]
    EE --> FF[Confirmar transferencia]
    FF --> GG{¿Transferencia exitosa?}
    
    GG -->|No| HH[❌ Error en transferencia]
    HH --> II[Revisar datos bancarios]
    II --> JJ[Contactar vendedor]
    JJ --> KK[Corregir información]
    KK --> DD
    
    GG -->|Sí| LL[✅ Transferencia completada]
    LL --> MM[Actualizar estado en sistema]
    MM --> NN[Marcar como 'Completado']
    NN --> OO[📄 Adjuntar comprobante]
    OO --> PP[📧 Notificar vendedor]
    PP --> QQ[Registrar en historial]
    QQ --> RR[✅ Depósito finalizado]
    RR --> D
    
    %% HERRAMIENTAS ADICIONALES
    D -->|Configuración| SS[⚙️ Herramientas admin]
    SS --> TT[💰 Ajustar % comisión<br/>👥 Gestionar usuarios<br/>📊 Generar reportes<br/>📧 Enviar notificaciones<br/>🔍 Auditoría sistema]
    TT --> D
    
    D -->|Soporte| UU[🎫 Tickets pendientes<br/>💬 Chat con usuarios<br/>📞 Llamadas registradas<br/>📋 FAQ actualización]
    UU --> D
    
    %% ESTILOS
    classDef admin fill:#fff3e0
    classDef deposito fill:#e8f5e8
    classDef verificacion fill:#e3f2fd
    classDef decision fill:#f3e5f5
    classDef error fill:#ffebee
    classDef success fill:#e8f5e8
    
    class A,B,C,E,F,G,H,I,SS,TT,UU admin
    class M,N,O,P,Q,AA,BB,CC,DD,EE,FF,LL,MM,NN,OO,PP,QQ,RR deposito
    class S,T,X,Y,Z verificacion
    class D,J,R,T,GG decision
    class HH,II,JJ,KK error
    class LL,RR,X success
```

## 💳 FLUJO ESPECÍFICO DEL PROCESO DE PAGO

```mermaid
flowchart TD
    %% INICIO PAGO
    A[💳 Cliente procede al pago] --> B[Validar carrito]
    B --> C{¿Carrito válido?}
    C -->|No| D[❌ Error: carrito vacío]
    D --> E[Redirigir al carrito]
    
    C -->|Sí| F[Completar datos envío]
    F --> G[Validar formulario]
    G --> H{¿Datos completos?}
    H -->|No| I[❌ Mostrar errores]
    I --> F
    
    H -->|Sí| J[Mostrar método de pago]
    J --> K[Configurar Stripe Elements]
    K --> L[Usuario ingresa tarjeta]
    L --> M[Validar datos tarjeta]
    M --> N{¿Tarjeta válida?}
    N -->|No| O[❌ Error de validación]
    O --> L
    
    %% PROCESAMIENTO STRIPE
    N -->|Sí| P[Crear Payment Method]
    P --> Q[Crear orden en BD]
    Q --> R[Obtener vendedores del carrito]
    R --> S[Verificar cuentas Stripe Connect]
    S --> T{¿Connect disponible?}
    
    %% STRIPE CONNECT VS DIRECTO
    T -->|Sí| U[🔄 Modo Connect]
    U --> V[Crear pago con división automática]
    V --> W[Stripe distribuye fondos]
    
    T -->|No| X[🔄 Modo Directo]
    X --> Y[Crear pago simple total]
    Y --> Z[Calcular comisión localmente]
    Z --> AA[💰 Platform fee: 15%<br/>💵 Seller amount: 85%]
    
    %% CONFIRMACIÓN DE PAGO
    V --> BB[Confirmar pago]
    AA --> BB
    BB --> CC{¿Pago exitoso?}
    
    %% MANEJO DE ERRORES DE PAGO
    CC -->|No| DD[Analizar tipo de error]
    DD --> EE{¿Tipo de error?}
    EE -->|Tarjeta rechazada| FF[❌ Error: fondos insuficientes]
    EE -->|Datos incorrectos| GG[❌ Error: verificar datos]
    EE -->|Error de red| HH[❌ Error: conexión]
    EE -->|Límite excedido| II[❌ Error: límite diario]
    
    FF --> JJ[Mostrar mensaje específico]
    GG --> JJ
    HH --> JJ
    II --> JJ
    JJ --> KK[Opción de reintentar]
    KK --> LL{¿Reintentar?}
    LL -->|Sí| L
    LL -->|No| MM[Abandonar compra]
    
    %% PAGO EXITOSO
    CC -->|Sí| NN[✅ Pago confirmado]
    NN --> OO[Actualizar orden status]
    OO --> PP[Guardar datos de pago]
    PP --> QQ[Limpiar carrito]
    QQ --> RR[Crear registro comisión]
    RR --> SS[📧 Email cliente]
    SS --> TT[📧 Email vendedores]
    TT --> UU[🔔 Notificación admin]
    UU --> VV[Redirigir a confirmación]
    VV --> WW[✅ Compra completada]
    
    %% WEBHOOK STRIPE
    NN --> XX[⚡ Stripe Webhook]
    XX --> YY[Verificar firma webhook]
    YY --> ZZ{¿Webhook válido?}
    ZZ -->|No| AAA[❌ Rechazar webhook]
    ZZ -->|Sí| BBB[Procesar evento]
    BBB --> CCC[Actualizar estado final]
    CCC --> DDD[Log de auditoría]
    
    %% ESTILOS
    classDef cliente fill:#e1f5fe
    classDef pago fill:#fce4ec
    classDef stripe fill:#e8eaf6
    classDef error fill:#ffebee
    classDef success fill:#e8f5e8
    classDef decision fill:#f3e5f5
    classDef webhook fill:#f3e5f5
    
    class A,F,L,VV,WW cliente
    class J,K,P,Q,V,Y,BB,NN,OO,PP,QQ,RR pago
    class R,S,U,V,W,X,Y,Z,AA stripe
    class D,I,O,FF,GG,HH,II,JJ,AAA error
    class NN,WW,CCC success
    class C,H,N,T,CC,EE,LL,ZZ decision
    class XX,YY,BBB,DDD webhook
```

## 📊 FLUJO DE ESTADOS DE LA ORDEN

```mermaid
stateDiagram-v2
    [*] --> Creando : Cliente procede al pago
    Creando --> Pendiente : Orden creada en BD
    Pendiente --> Pagado : Pago confirmado
    Pendiente --> Cancelado : Pago falló/cancelado
    
    Pagado --> Preparando : Vendedor confirma
    Preparando --> Enviado : Vendedor despacha
    Enviado --> Completado : Cliente confirma recepción
    
    Preparando --> Cancelado : Vendedor cancela
    Enviado --> Cancelado : Problema en envío
    
    Cancelado --> [*] : Proceso terminado
    Completado --> [*] : Proceso exitoso
    
    note right of Pagado : Comisión calculada automáticamente
    note right of Completado : Vendedor puede solicitar depósito
```

## 💰 FLUJO DE ESTADOS DEL DEPÓSITO

```mermaid
stateDiagram-v2
    [*] --> Generado : Venta completada
    Generado --> Pendiente : Comisión calculada
    Pendiente --> Procesando : Admin inicia depósito
    Procesando --> Completado : Transferencia exitosa
    Procesando --> Fallido : Error en transferencia
    
    Fallido --> Pendiente : Reintento programado
    Completado --> [*] : Vendedor recibe fondos
    
    note right of Pendiente : 85% del total de venta
    note right of Completado : Vendedor notificado por email
```

## 🔄 FLUJO COMPLETO INTEGRADO

```mermaid
flowchart TD
    %% PROCESO COMPLETO DE PRINCIPIO A FIN
    Start([🚀 INICIO]) --> A[👤 Cliente navega tienda]
    A --> B[🛒 Selecciona productos]
    B --> C[📋 Checkout y pago]
    C --> D[💳 Stripe procesa $1000]
    D --> E[✅ Pago exitoso]
    
    E --> F[📊 Sistema calcula automáticamente]
    F --> G[💸 Comisión plataforma: $150 15%]
    G --> H[💵 Para vendedor: $850 85%]
    
    H --> I[📧 Notificaciones enviadas]
    I --> J[🏪 Vendedor ve venta en dashboard]
    I --> K[⚡ Admin ve comisión generada]
    
    J --> L[📦 Vendedor prepara producto]
    L --> M[🚚 Vendedor envía producto]
    M --> N[✅ Cliente recibe producto]
    
    K --> O{Admin procesa depósito?}
    O -->|⏳ No| P[💰 Balance pendiente acumula]
    P --> O
    O -->|✅ Sí| Q[🏦 Admin hace transferencia]
    Q --> R[💵 Vendedor recibe $850]
    R --> S[📧 Confirmación de depósito]
    
    N --> T[⭐ Proceso completado]
    S --> T
    T --> End([🎯 FIN])
    
    %% MÉTRICAS DEL PROCESO
    subgraph Métricas [📊 MÉTRICAS DEL PROCESO]
        M1[💰 Cliente pagó: $1000]
        M2[💸 Plataforma retuvo: $150]
        M3[💵 Vendedor recibió: $850]
        M4[⏱️ Tiempo total: 2-3 días]
        M5[😊 Satisfacción: ⭐⭐⭐⭐⭐]
    end
    
    %% ESTILOS
    classDef cliente fill:#e1f5fe,stroke:#01579b,stroke-width:2px
    classDef vendedor fill:#e8f5e8,stroke:#2e7d32,stroke-width:2px
    classDef admin fill:#fff3e0,stroke:#ef6c00,stroke-width:2px
    classDef pago fill:#fce4ec,stroke:#c2185b,stroke-width:2px
    classDef sistema fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px
    classDef metricas fill:#e0f2f1,stroke:#00695c,stroke-width:2px
    
    class A,B,C,N cliente
    class J,L,M,R vendedor
    class K,O,Q admin
    class D,E pago
    class F,G,H,I sistema
    class M1,M2,M3,M4,M5 metricas
```

---

## 📋 DESCRIPCIÓN DE ELEMENTOS DEL DIAGRAMA

### 🔷 **Símbolos Utilizados**
- **🔴 Círculos**: Puntos de inicio y fin
- **📦 Rectángulos**: Procesos y acciones
- **💎 Rombos**: Decisiones y bifurcaciones
- **📊 Subgrafos**: Agrupación de procesos relacionados
- **🔄 Estados**: Transiciones de estado

### 🎨 **Código de Colores**
- **🔵 Azul**: Acciones del cliente
- **🟢 Verde**: Acciones del vendedor
- **🟠 Naranja**: Acciones del administrador
- **🔴 Rosa**: Procesos de pago
- **🟣 Morado**: Procesos del sistema
- **⚪ Gris**: Decisiones y validaciones

### 🔗 **Tipos de Conexiones**
- **→ Flecha sólida**: Flujo normal
- **⟶ Flecha punteada**: Flujo condicional
- **↺ Flecha curva**: Bucle o repetición
- **⟲ Flecha doble**: Proceso bidireccional

---

**📅 Creado:** Noviembre 2025  
**🔧 Formato:** Mermaid.js  
**📊 Cobertura:** 100% del proceso de compra  
**🎯 Actores:** Cliente, Vendedor, Administrador  
**💰 Sistema:** Comisiones automáticas 15%/85%**