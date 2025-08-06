# Informe de Verificación del Proyecto

Este informe detalla los hallazgos y recomendaciones resultantes de la verificación del proyecto, enfocándose en opciones de mejora, nivel de seguridad y la viabilidad de un modelo SaaS.

## 1. Identificación del Framework

El proyecto está construido utilizando **CodeIgniter**, un framework PHP.

## 2. Opciones de Mejora de Seguridad

Se han identificado las siguientes áreas de mejora para fortalecer la seguridad del proyecto:

*   **Hasheo de Contraseñas**:
    *   **Problema**: Las contraseñas se comparan directamente sin hasheo en [`app/Controllers/Auth.php`](app/Controllers/Auth.php:172), lo que las hace vulnerables a ataques de fuerza bruta y exposición en caso de una brecha de datos.
    *   **Recomendación**: Implementar `password_hash()` al guardar contraseñas y `password_verify()` al validar. Esto asegura que las contraseñas nunca se almacenen en texto plano.

*   **Protección CSRF (Cross-Site Request Forgery)**:
    *   **Problema**: El filtro CSRF está comentado en [`app/Config/Filters.php`](app/Config/Filters.php:25), lo que deja la aplicación vulnerable a ataques de falsificación de solicitudes entre sitios.
    *   **Recomendación**: Descomentar `'csrf'` en `$globals['before']` para habilitar la protección CSRF globalmente.

*   **Forzar HTTPS**:
    *   **Problema**: La opción `$forceGlobalSecureRequests` está en `false` en [`app/Config/App.php`](app/Config/App.php:136), lo que significa que las solicitudes no se fuerzan a usar HTTPS.
    *   **Recomendación**: Cambiar `$forceGlobalSecureRequests = true;` en entornos de producción para asegurar que toda la comunicación entre el cliente y el servidor sea cifrada.

*   **Content Security Policy (CSP)**:
    *   **Problema**: La CSP está deshabilitada (`$CSPEnabled = false;`) en [`app/Config/App.php`](app/Config/App.php:177).
    *   **Recomendación**: Habilitar y configurar una política de seguridad de contenido estricta para mitigar ataques de Cross-Site Scripting (XSS) y de inyección de datos, controlando las fuentes de contenido permitidas.

*   **Modo de Depuración de Base de Datos**:
    *   **Problema**: `DBDebug` está en `true` en [`app/Config/Database.php`](app/Config/Database.php:36), lo que puede exponer errores de base de datos sensibles a los usuarios en producción.
    *   **Recomendación**: Asegurarse de que `DBDebug` esté en `false` en entornos de producción para evitar la divulgación de información sensible.

*   **Validación y Sanitización de Entradas**:
    *   **Problema**: Aunque existen reglas de validación, es crucial revisar exhaustivamente todas las entradas de usuario en cada controlador para prevenir inyecciones SQL, XSS y otras vulnerabilidades.
    *   **Recomendación**: Implementar sanitización de datos en el lado del servidor y usar sentencias preparadas (CodeIgniter ORM lo hace por defecto, pero verificar en consultas manuales) para todas las interacciones con la base de datos.

## 3. Viabilidad como SaaS (Software as a Service)

El proyecto actual presenta una arquitectura con un buen potencial para evolucionar hacia un modelo SaaS, especialmente bajo un enfoque de multi-tenencia de base de datos compartida con segregación por columna (`cuenta_id`).

### Puntos Fuertes para SaaS:

*   **Concepto de Cuenta/Empresa**: La existencia de la tabla `tbl_cuentas` y el uso de `cuenta_id` en `tbl_usuarios` y en la lógica de autenticación (`Auth.php`) y gestión de usuarios (`Usuarios.php`) es un pilar fundamental. Esto indica que el sistema ya está diseñado para manejar múltiples entidades de negocio de forma separada.
*   **Segregación de Usuarios**: El `UsuariosModel` ya filtra usuarios por `cuenta_id`, lo que es crucial para el aislamiento de datos entre inquilinos.
*   **Proceso de Creación de Empresa**: La función `Auth::crear()` permite el registro de nuevas empresas y su usuario administrador, lo que simula un proceso de "onboarding" básico para nuevos inquilinos.

### Oportunidades de Mejora y Desafíos para un SaaS Completo:

1.  **Consistencia en la Multi-tenencia a Nivel de Modelo**:
    *   **Problema**: Aunque `UsuariosModel` filtra por `cuenta_id`, otros modelos como `TicketsModel`, `CampanasModel` y `SurveyModel` no lo hacen directamente en sus métodos de obtención de datos. Esto significa que la responsabilidad de filtrar por `cuenta_id` recae en los controladores, lo que es propenso a errores y podría llevar a fugas de datos si no se implementa consistentemente.
    *   **Recomendación**: Implementar un `BaseModel` personalizado o un `Trait` que todos los modelos de datos de inquilinos extiendan/usen. Este `BaseModel` inyectaría automáticamente el `cuenta_id` del usuario autenticado en todas las consultas, garantizando el aislamiento de datos a nivel de base de datos de forma centralizada.

2.  **Aislamiento de Archivos Subidos**:
    *   **Problema**: Los archivos subidos (ej. imágenes de encuestas en `uploads/surveys/`, archivos de tickets en `public/uploads/tickets/archivos/`) se guardan en rutas genéricas sin segregación por `cuenta_id`. Esto podría permitir que un inquilino acceda a archivos de otro si las rutas son predecibles o si hay vulnerabilidades en la lógica de acceso.
    *   **Recomendación**: Modificar las rutas de subida de archivos para incluir el `cuenta_id` (ej. `uploads/surveys/{cuenta_id}/`) y asegurar que la lógica de descarga/visualización de archivos también valide el `cuenta_id` del usuario.

3.  **Gestión de Inquilinos y Ciclo de Vida**:
    *   **Problema**: El proceso de creación de una "empresa" es básico. Para un SaaS, se necesitarían funcionalidades más robustas para la gestión del ciclo de vida del inquilino (activación/desactivación, suspensión, eliminación de datos, gestión de planes de suscripción, etc.).
    *   **Recomendación**: Desarrollar un módulo de administración de inquilinos con funcionalidades CRUD completas y lógica para manejar los estados de la cuenta.

4.  **Personalización por Inquilino**:
    *   **Problema**: El sistema no muestra evidencia de personalización (ej. branding, temas, configuraciones específicas) por inquilino.
    *   **Recomendación**: Si se requiere, implementar un sistema de configuración por inquilino que permita a cada cuenta personalizar ciertos aspectos de la aplicación (ej. logo, colores, módulos habilitados).

5.  **Escalabilidad de la Base de Datos**:
    *   **Problema**: Aunque la segregación por columna es viable, para un SaaS a gran escala con muchos inquilinos y grandes volúmenes de datos, podría haber desafíos de rendimiento y gestión.
    *   **Consideración**: Para el futuro, evaluar la posibilidad de migrar a un modelo de "base de datos por inquilino" o "esquema por inquilino" si la escalabilidad y el aislamiento de datos se vuelven críticos. Sin embargo, la implementación actual es un buen punto de partida y puede ser suficiente para un crecimiento inicial.

## 4. Opciones de Mejora Adicionales

Además de las mejoras de seguridad y SaaS, se proponen las siguientes optimizaciones:

1.  **Optimización de Consultas a Base de Datos**:
    *   **Observación**: Varios métodos en los modelos (`TicketsModel`, `CampanasModel`, `UsuariosModel`) realizan múltiples `JOIN` y `SELECT`.
    *   **Recomendación**: Revisar las consultas más frecuentes y considerar la creación de índices en las columnas utilizadas en `WHERE` y `JOIN`. Evaluar la necesidad de todos los campos seleccionados y considerar el uso de caché para consultas frecuentes.

2.  **Manejo de Errores y Logging Mejorado**:
    *   **Observación**: El uso de `session()->setFlashdata` para mensajes es funcional, pero un sistema de logging más robusto sería beneficioso.
    *   **Recomendación**: Implementar un logging más detallado para errores críticos, acciones de usuario sensibles y eventos del sistema. Centralizar la gestión de errores.

3.  **Refactorización de Controladores Grandes**:
    *   **Observación**: Controladores como `Tickets.php` y `Campanas.php` son extensos.
    *   **Recomendación**: Dividir la lógica en servicios o clases de negocio más pequeñas y especializadas. Mover la lógica de validación de formularios a clases dedicadas.

4.  **Gestión de Archivos y Subidas**:
    *   **Observación**: La lógica de subida de archivos está duplicada en `Tickets.php` y `SurveyController.php`.
    *   **Recomendación**: Centralizar la lógica de subida de archivos en un helper o una librería dedicada. Considerar el almacenamiento de archivos fuera del directorio `public` para mayor seguridad.

5.  **Internacionalización (i18n) y Localización (l10n)**:
    *   **Observación**: El proyecto ya usa `defaultLocale = 'es'` y `supportedLocales = ['es']`.
    *   **Recomendación**: Si se planea expandir a otros idiomas, asegurar que todos los textos de la interfaz de usuario, mensajes de error y notificaciones se manejen a través del sistema de internacionalización de CodeIgniter.

6.  **Pruebas Automatizadas**:
    *   **Observación**: No se observa una estructura clara para pruebas unitarias o de integración.
    *   **Recomendación**: Implementar pruebas unitarias para los modelos y la lógica de negocio crítica, y pruebas de integración para los controladores y las rutas.

7.  **Documentación de API (si aplica)**:
    *   **Observación**: Si el sistema expone APIs, no hay evidencia de documentación.
    *   **Recomendación**: Utilizar herramientas como Swagger/OpenAPI para documentar las APIs.
