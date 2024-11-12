# Configuración de Seguridad Incorrecta

## ¿Qué es la configuración de seguridad incorrecta?

La configuración de seguridad incorrecta en Laravel se refiere a cualquier ajuste o parámetro de seguridad que no se ha implementado de acuerdo con las mejores prácticas o que deja la aplicación vulnerable a ataques. Laravel, aunque viene con muchas características de seguridad integradas, requiere una configuración adecuada para garantizar la máxima protección.

## Impacto de la configuración de seguridad incorrecta

Una configuración de seguridad incorrecta puede tener graves consecuencias para una aplicación Laravel, incluyendo:
 
 - Acceso no autorizado: Si las configuraciones de autenticación y autorización no son estrictas, los atacantes podrían obtener acceso a áreas restringidas de la aplicación o a datos sensibles.
- Inyección de código: Si no se implementan correctamente las protecciones contra SQLi o XSS, los atacantes podrían ejecutar código malicioso en la aplicación, lo que podría llevar al robo de datos o a la toma de control del servidor.
- Exposición de datos sensible: Si los archivos de configuración o los registros de errores contienen información confidencial, como credenciales de bases de datos o claves de API, los atacantes podrían acceder a ellos y comprometer la seguridad de la aplicación.
- Ataques de denegación de servicio (DoS): Si no se configuran adecuadamente los límites de solicitudes o las protecciones contra ataques de fuerza bruta, los atacantes podrían sobrecargar la aplicación y hacerla inaccesible para los usuarios legítimos.

## ¿Cómo ocurre la configuración de seguridad incorrecta en Laravel?

La configuración de seguridad incorrecta puede ocurrir de varias maneras:

- Uso de valores predeterminados inseguros: Laravel viene con algunos valores predeterminados que pueden no ser adecuados para todos los entornos de producción. Es importante revisar y ajustar estos valores según las necesidades de seguridad de la aplicación.
- Desconocimiento de las mejores prácticas: Los desarrolladores pueden no estar familiarizados con las mejores prácticas de seguridad de Laravel o pueden pasar por alto algunos aspectos importantes de la configuración.
- Falta de pruebas de seguridad: No realizar pruebas de seguridad exhaustivas antes de desplegar la aplicación puede dejar vulnerabilidades ocultas que los atacantes pueden explotar.
- Actualizaciones de seguridad pendientes: No aplicar las actualizaciones de seguridad de Laravel de manera oportuna puede dejar la aplicación vulnerable a ataques conocidos.

## Mitigación de configuración de seguridad incorrecta

Laravel proporciona herramientas y convenciones para configurar una aplicación de forma segura ([ver documentación oficial Laravel Configuration](https://laravel.com/docs/11.x/configuration)).

### Modo de depuración

En el archivo `.env` el modo de depuración `APP_DEBUG` debe estar desactivado en el entorno de producción. Esto evitará que se muestren mensajes de error detallados que podrían revelar información sensible sobre la aplicación.

```env

APP_DEBUG=false

```

### Permisos de archivos y directorios

Restringir el acceso a la carpeta storage configurando los permisos de archivo adecuados para evitar modificaciones no autorizadas.

```bash

# configuración de la carpeta storage
chmod -R 755 storage

```

### Configuración de servidor

La correcta configuración del servidor web (Apache, Nginx, etc.) incluye desactivar directorios de listado, configurar encabezados de seguridad HTTP y utilizar HTTPS para cifrar el tráfico.

```htaccess

# Configuración de Apache
<IfModule mod_negotiation.c>
    Options -MultiViews -Indexes
</IfModule>

```

```php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Server', '');
        $response->headers->set('X-Powered-By', '');

        return $response;
    }
}

```

### Configuración de CORS

Laravel facilita la configuración de CORS a través del middleware CORS ([ver documentación oficial Laravel Routing - CORS](https://laravel.com/docs/11.x/routing#cors)). Se puede personalizar el comportamiento en el archivo `config/cors.php`.

```bash

# Configuración de CORS
php artisan config:publish cors

```

La configuración de seguridad de CORS se define de la siguiente forma:

- **paths**: Define las rutas a las que se aplicará el middleware CORS.
- **allowed_methods**: Especifica los métodos HTTP permitidos.
- **allowed_origins**: Lista los orígenes permitidos.
- **allowed_origins_patterns**: Permite definir patrones de orígenes permitidos usando expresiones regulares.
- **allowed_headers**: Lista los encabezados permitidos en las solicitudes Cross-Origin.
- **exposed_headers**: Especifica los encabezados que el navegador puede acceder desde JavaScript.
- **max_age**: Define el tiempo en segundos que el navegador puede almacenar en caché los resultados de la solicitud de verificación previa de CORS.
- **supports_credentials**: Habilita o deshabilita el envío de credenciales (cookies, encabezados de autenticación) en solicitudes Cross-Origin.

```php

// Configuración de CORS
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
    'allowed_origins' => ['https://midominio.com', 'https://otrodominio.com'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'Authorization'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];

```

Para más detalles se puede consultar el sitio web oficial de Mozilla en inglés ([ver documentación oficial Mozilla - Cross-Origin Resource Sharing (CORS)](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS))

- Comandos de optimización.

Laravel dispone de un comando para optimizar la configuración y algunos componentes ([ver documentación oficial de Laravel Deployment - Optimization](https://laravel.com/docs/11.x/deployment#optimization)).

```bash

# Optimización de configuración
php artisan optimize

```

- Credenciales seguras

Contraseñas fuertes y robustas para las bases de datos, paneles de administración y cualquier otro servicio relacionado con la aplicación, y almacenamiento seguro como contraseñas encriptadas mediante Hash de Laravel para hacer hash de las contraseñas de forma segura. ([ver documentación oficial de ASAWL - Explotación de datos sensibles](./explotacion-datos-sensibles.md)).

- Actualizaciones de seguridad

Actualizar Laravel y todas sus dependencias para utilizar las últimas correcciones de seguridad. ([ver documentación oficial de ASAWL - Componentes vulnerables y desactualizados](./componentes-vulnerables-desactualizados.md)).
