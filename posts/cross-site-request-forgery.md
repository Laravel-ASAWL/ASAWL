---
title: Cross-Site Request Forgery (CSRF)
---
# Cross-Site Request Forgery (CSRF)

## ¿Qué es Cross-Site Request Forgery?

CSRF o Cross-Site Request Forgery, es un tipo de vulnerabilidad de seguridad en aplicaciones web que permite a un atacante engañar al navegador de un usuario autenticado para que realice acciones no deseadas en un sitio web en el que el usuario ya ha iniciado sesión. En otras palabras, el atacante aprovecha la confianza que el sitio web tiene en el usuario para realizar acciones en su nombre sin su conocimiento o consentimiento.

## Impacto de CSRF

El impacto de un ataque CSRF puede ser significativo y depende de las acciones que el atacante pueda realizar en nombre del usuario. Algunos de los posibles impactos incluyen:

- Cambio de datos de la cuenta: El atacante podría cambiar la contraseña del usuario, la dirección de correo electrónico u otros datos personales.
- Realización de transacciones no autorizadas: El atacante podría realizar compras, transferencias de dinero u otras transacciones financieras en nombre del usuario.
- Publicación de contenido no deseado: El atacante podría publicar comentarios, mensajes o cualquier otro tipo de contenido en nombre del usuario.
- Cambio de configuración de la aplicación: El atacante podría modificar la configuración de la aplicación, como la configuración de privacidad o seguridad.
- Acceso a datos sensibles: En algunos casos, el atacante podría incluso acceder a datos sensibles del usuario si la aplicación no implementa correctamente la autorización.

## ¿Cómo ocurre un ataque CSRF?

Un ataque CSRF generalmente ocurre de la siguiente manera:

1. El usuario inicia sesión en una aplicación web legítima.
2. El usuario visita un sitio web malicioso o hace clic en un enlace malicioso. Este sitio web o enlace contiene código oculto que realiza una solicitud a la aplicación web legítima en nombre del usuario.
3. El navegador del usuario, que aún tiene una sesión activa en la aplicación web legítima, envía la solicitud maliciosa sin que el usuario se dé cuenta.
4. La aplicación web legítima procesa la solicitud como si fuera legítima, ya que proviene del navegador del usuario autenticado.
5. El atacante logra realizar acciones no deseadas en la aplicación web en nombre del usuario.

## Mitigation de Cross-Site Request Forgery (CSRF)

Laravel proporciona mecanismos integrados para proteger la aplicación contra ataques CSRF ([]()):

### Middleware VerifyCsrfToken

Laravel incluye el middleware VerifyCsrfToken por defecto, que verifica automáticamente la presencia de un token CSRF válido en cada solicitud POST, PUT, PATCH o DELETE. Este token se genera automáticamente y se incluye en todos los formularios de la aplicación web ([ver documentación oficial de Laravel CSRF Protection](https://laravel.com/docs/11.x/csrf)).

### Directiva @csrf en Blade

En las vistas Blade, se utiliza la directiva @csrf dentro de los formularios para generar el campo oculto con el token CSRF ([ver documentación oficial de Laravel CSRF Protection](https://laravel.com/docs/11.x/csrf#preventing-csrf-requests)):

```php

<form method="POST">
    {{-- Uso de la protección CSRF en la vista --}}
    @csrf

    {{-- Detalles del formulario --}}
</form>

```

### Validación manual del token CSRF

Si se necesita validar el token CSRF manualmente (por ejemplo, en una solicitud AJAX), se puede utilizar el método $request->validate() o el facade Validator:

```php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/profile', function(Request $request){
    $request->validated([
        '_token' => 'required', // Validar el token de forma manual

    ]);
});

```

### Exclusión de rutas del middleware CSRF

En algunos casos, es posible excluir ciertas rutas de la protección CSRF (por ejemplo, para Webhooks o APIs externas), registrando la exclusión de los patrones de ruta en el configuración de los middleware de Laravel ([ver archivo de configuración de Laravel](./bootstrap/app.php))

```php

use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Implementar el middleware de seguridad
        $middleware->append(SecurityHeadersMiddleware::class);
        
        // Excluir validación CSRF para las rutas
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'http://example.com/foo/bar',
            'http://example.com/foo/*',
        ]);

})
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

```

Adicional se detallan algunas recomendaciones de seguridad en la protección CSRF:

- Asegurar que el middleware VerifyCsrfToken esté habilitado en el grupo de middleware web.
- Nunca desactivar la protección CSRF a menos que sea absolutamente necesario y se comprenda las implicaciones de seguridad que con lleva.
- Utilizar tokens CSRF en todas las solicitudes que modifiquen el estado de la aplicación.
- Considerar la posibilidad de utilizar SameSite cookies para una mayor protección contra CSRF.
