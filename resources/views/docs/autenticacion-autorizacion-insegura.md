# Autenticación y autorización insegura

## ¿Qué es la autenticación y autorización insegura?

La autenticación y autorización son dos procesos fundamentales en la seguridad de las aplicaciones web.

- Autenticación: Es el proceso de verificar la identidad de un usuario, es decir, confirmar que es quien dice ser. Esto generalmente se hace mediante el uso de credenciales, como un nombre de usuario y contraseña.
- Autorización: Es el proceso de determinar qué acciones o recursos puede acceder un usuario autenticado. Esto asegura que los usuarios solo puedan realizar las acciones para las que tienen permiso.

La autenticación y autorización insegura se refiere a cualquier debilidad o vulnerabilidad en estos procesos que puede ser explotada por un atacante para obtener acceso no autorizado a la aplicación o a los datos de los usuarios.

## Impacto de la autenticación y autorización insegura

Las consecuencias de una autenticación y autorización insegura pueden ser graves:

- Acceso no autorizado a cuentas de usuario: Un atacante podría obtener acceso a la cuenta de un usuario legítimo y realizar acciones en su nombre, como cambiar la información de la cuenta, realizar compras o enviar mensajes.
- Acceso no autorizado a datos sensibles: Si la autorización es débil, un atacante podría acceder a datos confidenciales, como información personal, financiera o de salud.
- Elevación de privilegios: Un atacante podría explotar vulnerabilidades para obtener acceso a funciones o recursos a los que no debería tener acceso, como la administración del sistema o la modificación de datos críticos.
- Toma de control de la aplicación: En el peor de los casos, un atacante podría obtener control total de la aplicación y utilizarla para realizar actividades maliciosas, como distribuir malware o lanzar ataques a otros sistemas.

## ¿Cómo ocurre la autenticación y autorización insegura?

Existen varias formas en que la autenticación y autorización pueden ser inseguras:

- Credenciales débiles: Contraseñas cortas o fáciles de adivinar, preguntas de seguridad predecibles o falta de mecanismos de autenticación de múltiples factores.
- Almacenamiento inseguro de credenciales: Almacenar contraseñas en texto plano o utilizar algoritmos de hash débiles.
- Sesiones inseguras: No implementar medidas de seguridad adecuadas para proteger las sesiones de usuario, como tiempos de espera de sesión cortos o tokens de sesión impredecibles.
- Validación de entrada deficiente: No validar adecuadamente los datos de entrada, lo que puede permitir ataques de inyección de código o manipulación de parámetros.
- Lógica de autorización defectuosa: Errores en la implementación de las reglas de autorización que permiten a los usuarios acceder a recursos a los que no deberían tener acceso.

## Mitigación de autenticación y autorización insegura

Laravel simplifica enormemente la implementación de autenticación y autorización gracias a sus características integradas y a su estructura clara.

### 1. Autenticación

Laravel facilita la autenticación mediante el uso de varios métodos:

#### Uso de Scaffolding

Laravel proporciona un comando para generar rápidamente todo el código necesario para la autenticación básica, sin necesidad de instalar otro paquete externo ([ver documentación oficial Laravel UI](https://github.com/laravel/ui)):

```bash
# terminal

# Instalar Laravel UI
composer require laravel/ui
php artisan ui:auth

```

los comandos anteriores instalan el paquete `laravel/ui` y crea: vistas, controladores y rutas; para el registro, inicio de sesión, recuperación de contraseña, verificación de correos electrónicos, etc.

#### Middleware Auth

Se puede proteger rutas específicas utilizando el Middleware Auth ([ver documentación oficial Laravel Middleware](https://laravel.com/docs/11.x/middleware)):

```php
# web.php

use Illuminate\Support\Facades\Route;

...

Route::get('/dashboard', function(){
    // ...
})->middleware('auth');

```

Esto asegurará que solo los usuarios autenticados puedan acceder a la ruta /dashboard.

#### Función Auth

Uso de funciones para validar la autenticación ([ver documentación oficial Laravel Autenticación](https://laravel.com/docs/11.x/authentication)):

```php
# LoginController.php

use Illuminate\Support\Facades\Auth;

...

// Función para verificar si el usuario está autenticado
if(Auth::check()) {

  // Función para obtener el usuario autenticado
  $user = Auth::user();

  // Función para obtener el ID del usuario autenticado
  $id = Auth::id();

  // Función para cerrar sesión del usuario autenticado
  Auth::logout();
}

```

### 2. Autorización

Laravel ofrece dos mecanismos principales para la autorización:

#### Gates

Son funciones simples que determinan si un usuario puede realizar una acción específica ([ver documentación oficial Laravel Authorization - Gates](https://laravel.com/docs/11.x/authorization#gates)).

```php
# AppServiceProvider.php

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

...

public function boot(): void
{
  // Protección de acceso mediante Gate de autorización
  Gate::define('update-post', function (User $user, Post $post) {
      return $user->id === $post->user_id;
  });
}

```

#### Policies

Son clases que agrupan lógicamente las autorizaciones relacionadas con un modelo particular ([ver documentación oficial Laravel Authorization - Creating Policies](https://laravel.com/docs/11.x/authorization#creating-policies)).

```bash

# Creación de la clase PostPolicy
php artisan make:policy PostPolicy

```

El comando anterior genera el archivo app\Policies\PostPolicy.php en la carpeta Policies. ([ver archivo PostPolicy.php](./app/Policies/PostPolicy.php)).

```php
# PostPolicy.php

namespace App\Policies;
 
use App\Models\Post;
use App\Models\User;
 
class PostPolicy
{
  // Protección de acceso mediante Policy de autorización
  public function update(User $user, Post $post): bool
  {
      return $user->id === $post->user_id;
  }
}

```

Se puede utilizar Gate y Policy en los controladores y vistas para controlar el acceso a funcionalidades:

```php
# UserControlle.php

// Utilizando Gate en el Controlador
if(Gate::allows('update-post', $user, $post)) {
  // ...
}

// Utilizando Policy en el Controlador
if ($user->can('update', $post)) {
  // ...
}

```

```php
# user.blade.php

// Utilizando Policy en la Vista
@can()
  <a href="{{ route('post.edit', $post->id) }}">Editar</a>
@endcan

```

Adicional se detallan algunas recomendaciones de seguridad en la autenticación y la autorización:

- Implementar autenticación de dos factores mediante Laravel Fortify ([ver documentación oficial Laravel Fortify](https://laravel.com/docs/11.x/fortify)) para aumentar la seguridad de la aplicación.
- Considerar utilizar paquetes como Laravel Sanctum ([ver documentación oficial Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)) y Laravel Passport ([ver documentación oficial Laravel Passport](https://laravel.com/docs/11.x/passport)) para autenticación de APIs.
- Personalizar las vistas y controladores generados por el scaffolding para adaptarlos a las necesidades específicas.
- Utilizar roles y permisos para una autorización más granular y flexible.
