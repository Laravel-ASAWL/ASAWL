# Cross-Site Scripting (XSS)

## ¿Qué es el Cross-site scripting?

XSS o Cross-Site Scripting, es un tipo de vulnerabilidad de seguridad que afecta a las aplicaciones web. En esencia, permite a un atacante inyectar código malicioso, generalmente JavaScript, en una página web legítima. Este código malicioso luego se ejecuta en el navegador de otros usuarios que visitan la página, lo que puede tener graves consecuencias.

## Impacto del XSS

El impacto de un ataque XSS puede variar, pero algunos de los efectos más comunes incluyen:

- Robo de sesiones, como: robar las cookies de sesión del usuario, lo que le permite hacerse pasar por él y acceder a su cuenta en la aplicación web.
- Redirecciones maliciosas, como: redirigir al usuario a un sitio web malicioso, donde podría ser víctima de phishing u otros ataques.
- Cambios en la apariencia de la página, como: modificar el contenido de la página web, mostrando información falsa o engañosa.
- Ejecución de acciones no autorizadas, como: realizar acciones en nombre del usuario, como enviar mensajes o realizar compras.

## ¿Cómo ocurre un ataque XSS?

Un ataque XSS ocurre cuando una aplicación web Laravel no valida y sanitiza adecuadamente los datos ingresados por el usuario antes de mostrarlos en la página. Si un atacante puede inyectar código malicioso en estos datos, el navegador del usuario lo ejecutará sin saber que es malicioso.
Existen tres tipos principales de XSS:

1. XSS Reflejado (o No Persistente): El código malicioso se inyecta en la solicitud del usuario y se refleja de vuelta en la respuesta del servidor. Esto suele ocurrir a través de parámetros en la URL o en formularios.
2. XSS Almacenado (o Persistente): El código malicioso se almacena en el servidor, por ejemplo, en una base de datos, y se muestra a todos los usuarios que acceden a la página afectada.
3. XSS Basado en DOM: El código malicioso se inyecta directamente en el DOM del navegador, sin pasar por el servidor. Esto puede ocurrir cuando la aplicación web utiliza datos del usuario para modificar dinámicamente el contenido de la página.

## Mitigación de Cross-Site Scripting (XSS)

Laravel proporciona algunos métodos fundamentales para mitigar el XSS, el primero en la entrada de la información, el segundo en la salida de información y el tercero en la utilización de la protección Content-Security-Policy (CSP). A continuación, se detalla los tres procesos mediante ejemplos:

### El registro de datos en el controlador

- Validación de entradas de datos del usuario ([ver documentación oficial de Laravel Validation](https://laravel.com/docs/11.x/validation)).
- Sanitización de variables de información mediante la function e() ([ver documentación oficial de Laravel Strings - Method e()](https://laravel.com/docs/11.x/strings#method-e)).
- Sanitización de variables de información mediante la function trim() ([ver documentación oficial de PHP Function trim()](https://www.php.net/manual/en/function.trim.php)).
- Sanitización de variables de información mediante la function e() ([ver documentación oficial de PHP Function strip_tags()](https://www.php.net/manual/es/function.strip-tags.php)), y.
- Uso de Eloquent en la creación de registros ([ver documentación oficial de Laravel Eloquent](https://laravel.com/docs/11.x/eloquent)).

```php
# CommentController.php

// Validación de entradas
$validated = $request->validate([
    'comment' => 'required|string',
]);

// Sanitización de entradas
$comment = strip_tags(trim(e($validated['comment'])));

// Uso de Eloquent ORM
Comment::create([
    'comment' => $comment,
]);

```

### Mostrar los datos en la vista

- Utilización de la función e() para sanitizar información antes de mostrarla ([ver documentación oficial de Laravel Strings - Method e()](https://laravel.com/docs/11.x/strings#method-e)), y.
- Utilización de directivas {{ $variable }} para mostrar los datos en la vista ([ver documentación oficial de Laravel Blade - Displaying Data](https://laravel.com/docs/11.x/blade#displaying-data)).

```php
# comment.blade.php

@foreach ($comments as $comment)
<article class="...">
    <footer>...</footer>
    {{-- Mostrar datos sanitizados --}}
    <p class="...">{{ e($comment->comment) }}</p>
</article>
@endforeach

```

### Creación de un Midlleware para proteger las cabeceras HTTP mediante Content-Security-Policy (CSP):

- Creación de un middleware de seguridad ([ver documentación oficial de Laravel Middleware](https://laravel.com/docs/11.x/middleware)).

```bash
# terminal

# Creación del Middleware de seguridad
php artisan make:middleware SecurityHeadersMiddleware

```
- Configuración de cabeceras HTTP seguras ([ver archivo Middleware SecurityHeadersMiddleware](./app/Http/Middleware/SecurityHeadersMiddleware.php)).

```php
# SecurityHeadersMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', "default-src 'self';".
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com/;".
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net;".
            "img-src 'self' https://laravel.com https://flowbite.com;".
            "font-src 'self' https://fonts.bunny.net;"
        );

        return $response;
    }
}


```

- Registro del middleware en la configuración de Laravel ([ver archivo de configuración de Laravel](./bootstrap/app.php)).

```php
# app.php

use App\Http\Middleware\SecurityHeadersMiddleware;

...

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SecurityHeadersMiddleware::class);
    })
...

```

#### Detalle de las cabeceras HTTP seguras con Content-Security-Policy

**Directivas más utilizadas**

- **default-src**.- La directiva default-src es una directiva fundamental que actúa como un comodín o configuración por defecto para controlar la carga de varios tipos de recursos en una página web, a menos que se describan directivas más específicas para esos recursos.
- **script-src**.- La directiva script-src es esencial para controlar que scripts se pueden ejecutar en la aplicación web. Define los orígenes permitidos desde los cuales se pueden cargar y ejecutar scripts,
- **style-src**.- La directiva style-src controla que estilos CSS se pueden cargar y aplicar en la aplicación web.
- **img-src**.- La directiva img-src controla que imágenes se pueden cargar en la aplicación web.
- **font-src**.- La directiva font-src controla que fuentes tipográficas se pueden cargar y aplicar en la aplicación web.

Existe un listado completo de las directivas que se pueden configurar, las cuales se puede consultar en el sitio web de Mozilla en inglés ([ver documentación oficial de Mozilla HTTP Headers Content Security Policy](https://developer.mozilla.org/es/docs/Web/HTTP/Headers/Content-Security-Policy)).
