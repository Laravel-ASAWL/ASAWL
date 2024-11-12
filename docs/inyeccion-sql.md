# Inyección SQL (SQLi)

## ¿Qué es la inyección SQL?

SQLi o inyección SQL, es un tipo de ataque en el que un atacante inserta código SQL malicioso en campos de entrada de una aplicación, con el objetivo de manipular las consultas que la aplicación realiza a su base de datos.

## Impacto del SQLi

Los ataques SQLi pueden permitir al atacante:

- Leer datos confidenciales, como: números de tarjetas de crédito, contraseñas, información personal.
- Modificar o eliminar datos, como: alterar el contenido de la base de datos o borrar información crítica.
- Ejecutar comandos del sistema, como: tomar el control del servidor de la base de datos o del sistema operativo subyacente.

## ¿Cómo ocurre un ataque SQLi?

La inyección SQL en Laravel es una vulnerabilidad de seguridad que ocurre cuando un atacante logra insertar código malicioso en las consultas que la aplicación web envía a la base de datos. Esto puede suceder si no se valida y sanitiza adecuadamente las entradas del usuario antes de incluirlas en las consultas o utilizar consultas sin parametrizar.

### Acceso a datos sin credenciales:

1. Entrada Maliciosa: Un atacante introduce datos maliciosos en un campo de entrada de la aplicación web (formularios, URL, etc.). Por ejemplo, en la autentificación de una aplicación web en lugar del correo electrónico o la contraseña, ingresan una sentencia SQL maliciosa: `' OR 1=1 --`.

2. Consulta Vulnerable: Si el código no está protegido, esta entrada maliciosa se incorpora directamente a la consulta SQL. En el ejemplo anterior, la consulta podría transformarse en algo como: `SELECT * FROM users WHERE email = 'asawl@mail.com' and password '' OR 1=1 --`.

3. Ejecución del Código Malicioso: La base de datos interpreta el código SQL modificado. En este caso, la consulta para buscar usuarios mediante la condición de la coincidencia del correo electrónico y la contraseña, la condición `OR 1=1` siempre es verdadero, lo que significa que la condición se cumple para todos los registros. Además, `--` comenta el resto de la consulta, evitando la verificación de la contraseña.

4. Consecuencias: El atacante podría obtener acceso no autorizado a datos sensibles, modificar información en la base de datos o incluso ejecutar comandos en el sistema operativo si la base de datos tiene permisos suficientes.

### Acceso a datos con credenciales:

1. Entrada Maliciosa: Un atacante introduce datos maliciosos en un campo de entrada de la aplicación web (formularios, URL, etc.), en lugar de proporcionar un ID de producto válido, el atacante introduce una consulta SQL maliciosa que aprovecha la vulnerabilidad de la aplicación: `1 UNION SELECT email, password FROM users --`.

2. Consulta Vulnerable: Si la aplicación no valida ni sanitiza correctamente la entrada del usuario, esta entrada maliciosa se incorpora directamente en la consulta SQL que se envía a la base de datos. La consulta original que muestra un producto de acuerdo el identificador ingresado, debería ser: `SELECT name, description FROM products WHERE id = 1`; y que se transforma en: `SELECT name, description FROM products WHERE id = 1 UNION SELECT email, password FROM users --`.

3. Ejecución del Código Malicioso: La base de datos ejecuta la consulta SQL modificada. La primera parte de la consulta `SELECT name, description FROM productos WHERE id = 1` se ejecuta normalmente, pero debido al operador `UNION`, se combinan los resultados con los de la segunda consulta `SELECT email, password FROM users`. El comentario `--` anula el resto de la consulta original, evitando errores de sintaxis.

4. Consecuencias: La aplicación devuelve los resultados de ambas consultas combinados. Esto significa que, además de los detalles del producto (si existe uno con `ID` de valor `1`), la aplicación también mostrará los correos electrónicos de los usuarios y contraseñas almacenados en la tabla usuarios. El atacante ha obtenido acceso no autorizado a información sensible que no debería estar expuesta.

### Eliminar datos maliciosamente:

1. Entrada Maliciosa: Un atacante introduce datos maliciosos en un campo de entrada de la aplicación web (formularios, URL, etc.). Por ejemplo, en lugar de un correo electrónico válido, ingresan algo como: `fake@mail.com'; DROP TABLE users --`.

2. Consulta Vulnerable: Si el código no está protegido, esta entrada maliciosa se incorpora directamente a la consulta SQL. En el ejemplo anterior, la consulta podría transformarse en algo como: `SELECT email, password FROM users WHERE email = 'fake@mail.com'; DROP TABLE users --`.

3. Ejecución del Código Malicioso: La base de datos interpreta el código SQL modificado. En este caso, `DROP TABLE users`, lo que significa la tabla users se elimina de la base de datos. Además, `--` comenta el resto de la consulta.

4. Consecuencias: El atacante podría eliminar todos los registros de la tabla users de la base de datos e incluso todas las tablas si la base de datos tiene permisos suficientes.

## Mitigación de la inyección SQL (SQLi)

Laravel proporciona herramientas para proteger las aplicaciones web contra inyecciones SQL, pero sin embargo puede ser vulnerable.

```php

// Consulta vulnerable a inyección SQL
$user = DB::select("SELECT email, password FROM users WHERE email = '$request->email'");

```

La mitigación de la inyección SQL en Laravel se la realiza mediante:

**1. Validación de Entradas**

Validar rigurosamente todas las entradas del usuario para asegurar que cumplan con los formatos y tipos de datos esperados. Laravel ofrece una variedad de reglas de validación ([ver la documentación oficial de Laravel Validation](https://laravel.com/docs/11.x/validation)).

```php

// Validación de entradas
$validated = $request->validate([
    'email' => 'required|email',
    'password' => 'required',
]);

```

**2. Sanitización de Entradas**

Si se necesita incluir entradas del usuario directamente en consultas SQL (aunque no es recomendable), se debe utilizar las funciones de escape de Laravel para sanitizar los datos y evitar que se interpreten como código SQL ([ver documentación oficial de Laravel Strings - Method e()](https://laravel.com/docs/11.x/strings#method-e)).

```php

// Sanitización de entradas mediante la función e()
$email = e($validated['email']);
$password = e($validated['password']);

```

Se puede utilizar la función trim() para eliminar espacios en blanco ([ver documentación oficial de PHP Function trim()](https://www.php.net/manual/en/function.trim.php)).

```php

// Sanitización de entradas mediante la función trim()
$email = trim(e($validated['email']));
$password = trim(e($validated['password']));

```

Y adicional se puede utilizar la función strip_tags() para eliminar tags HTML ([ver documentación oficial de PHP Function strip_tags()](https://www.php.net/manual/es/function.strip-tags.php)).

```php

// Sanitización de entradas mediante strip_tags()
$email = strip_tags(trim(e($validated['email'])));
$password = strip_tags(trim(e($validated['password'])));

```

**3. Uso de Eloquent**

se debe utilizar Eloquent, el ORM de Laravel como constructor para parametrizar las consultas, Eloquent ayuda a evitar que las entradas maliciosas se interpreten como código SQL ([ver documentación oficial de Laravel Eloquent](https://laravel.com/docs/11.x/eloquent)).

```php

// Uso de Eloquent ORM
$user = User::where('email', $email)->first();

```

Si necesitas más flexibilidad que Eloquent, usa el Query Builder de Laravel, que también escapa los parámetros de forma segura ([ver documentación oficial de Laravel Queries](https://laravel.com/docs/11.x/queries)).

```php

// Uso de Query Builder
$user = DB::table('users')->where('email', $email)->get();

```

Si debes construir consultas SQL dinámicas, utiliza parámetros con nombre o marcadores de posición (?) para evitar que el código malicioso se interprete como parte de la consulta, estas consultas se denominan consultas parametrizadas ([ver documentación oficial de Laravel Database](https://laravel.com/docs/11.x/database#running-a-select-query)).

```php

// Uso de consultas parametrizadas
$user = DB::select('SELECT email, password FROM users WHERE email = ?', $email);

```

Para ilustrar la mitigación de la inyección SQL en Laravel, se puede revisar el código de la función login() de un controlador utilizado para el proceso de inicio de sesión de una aplicación web:

```php

public function login(Request $request)
{
    // 1. Validación de entradas
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 2. Sanitización de entradas
    $email = strip_tags(trim(e($validated['email'])));
    $password = strip_tags(trim(e($validated['password'])));

    // 3. Uso de Eloquent
    $user = User::where('email', $email)->first();

    ...
}

```
