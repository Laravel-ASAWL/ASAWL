# Fallos de validación de entrada

## ¿Qué es el fallo de validación de entrada?

Un fallo de validación de entrada ocurre cuando una aplicación web no verifica adecuadamente los datos proporcionados por el usuario antes de procesarlos o utilizarlos. Esto significa que la aplicación acepta cualquier tipo de entrada, incluso si es maliciosa o no cumple con el formato esperado.

## Impacto de los fallos de validación de entrada

Los fallos de validación de entrada pueden tener un impacto significativo en la seguridad de una aplicación web, ya que abren la puerta a una variedad de ataques, incluyendo:

- Inyección SQL (SQLi): Si la aplicación no valida la entrada, un atacante podría inyectar código malicioso, como SQL, que se ejecutará en el servidor o en el navegador del usuario. Esto puede llevar al robo de datos, modificación no autorizada de la aplicación o incluso la toma de control del servidor.
- Cross-Site Scripting (XSS): Como mencionamos anteriormente, XSS es un tipo de ataque que se aprovecha de la falta de validación de entrada para inyectar código malicioso en una página web.
- Ataques de denegación de servicio (DoS): Si la aplicación no limita la cantidad o el tipo de datos que acepta, un atacante podría enviar una gran cantidad de solicitudes o datos inválidos, lo que podría sobrecargar el servidor y hacerlo inaccesible para los
usuarios legítimos.
- Fugas de información: Si la aplicación no valida la entrada correctamente, podría revelar información sensible, como nombres de usuario, contraseñas o detalles de configuración, a un atacante.

## ¿Cómo ocurren los fallos de validación de entrada?

Los fallos de validación de entrada suelen ocurrir debido a:

- Falta de validación: La aplicación simplemente no realiza ninguna verificación de los datos ingresados por el usuario.
- Validación insuficiente: La aplicación realiza alguna validación, pero no es lo suficientemente estricta o completa para detectar todos los posibles ataques.
- Validación incorrecta: La aplicación realiza una validación, pero está mal implementada o contiene errores lógicos que pueden ser explotados por un atacante.
- Confianza excesiva en la validación del lado del cliente: La aplicación realiza la validación en el navegador del usuario, pero un atacante puede modificar fácilmente el código JavaScript para eludirla.

## Mitigación de fallos de validación de entrada

Para aplicar la validación de entrada en Laravel se lo puede hacer mediante los controladores ([ver documentación oficial de Laravel Validation](https://laravel.com/docs/11.x/validation)) o los Form Request ([ver documentación oficial de Laravel Validation - Form Request Validation](https://laravel.com/docs/11.x/validation#form-request-validation)):

### Validación en controladores

Se puede validar la entrada directamente en los controladores utilizando el método validate() del objeto Request ([ver archivo de definición del Controlador UserController](./app/Http/Controllers/UserController.php)):

```php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validateData = $request->validate*([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create($validateData);

        ...
    }

    ...
}


```

En el código anterior, se realiza la validación de: `name` obligatorio, de tipo cadena y con una longitud máxima de 255 caracteres. `email` obligatorio, con un formato válido y único en la tabla `users`, y `password` obligatorio, al menos 8 caracteres y coincidir con el campo de confirmación ([ver documentación oficial de Laravel de la validación de entradas](https://laravel.com/docs/11.x/validation)).

### Validación en formularios

Para una mejor organización y reutilización de la lógica de validación, se debe crear clases Form Request:

```bash

# Creación de Form Request
php artisan make:request StoreUserRequest

```

El comando anterior creará un archivo `app/Http/Requests/StoreUserRequest.php`. Dentro de este archivo, se puede definir las reglas de validación en el método `rules()` ([ver archivo de definición del Form Request StoreUserRequest](./app/Http/Requests/StoreUserRequest.php)):

```php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    ...

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }

    ...
}

```

Previa a la creación y a la configuración de reglas se podrá utilizar el Form Request en el controlador:

```php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        // la validación se realiza en el Form Request StoreUserRequest
        $validateData = $request->validated();

        User::create($validateData);

        ...
    }

    ...
}

```

### Personalización de mensajes de error

Se recomienda personalizar los mensajes de error de validación en el método `messages()` ([ver archivo de definición del Form Request StoreUserRequest](./app/Http/Requests/StoreUserRequest.php)):

```php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    ...

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.string' => 'El campo nombre debe ser una cadena de caracteres',
            'name.max' => 'El campo nombre debe tener menos de :max caracteres',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.email' => 'El campo correo electrónico no es válido',
            'email.unique' => 'El campo correo electrónico ya está registrado',
            'password.required' => 'La campo contraseña es obligatorio',
            'password.min' => 'La campo contraseña debe tener al menos :min caracteres',
            'password.confirmed' => 'Las campo contraseñas no coinciden',
        ];
    }
}

```

Adicional se detallan algunas recomendaciones de seguridad:

- Utilizar nombres de reglas descriptivos para facilitar la comprensión de la lógica de validación.
- Aprovechar las reglas de validación integradas de Laravel, solamente se debe crear reglas personalizadas si es estrictamente necesario.
- Se debe considerar la posibilidad de realizar validaciones adicionales en el lado del cliente (utilizando JavaScript) para proporcionar una retroalimentación más inmediata al usuario pero no confiar en la efectividad de ellas.
- Mantener el código de validación limpio y organizado para facilitar su mantenimiento y evolución.
