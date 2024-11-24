# Exposición de Datos Sensibles

## ¿Qué es la exposición de datos sensibles?

La exposición de datos sensibles se refiere a una situación en la que información confidencial o privada se vuelve accesible a personas o entidades no autorizadas. Estos datos pueden incluir información personal identificable (PII), como nombres, direcciones, números de seguridad social, información financiera, registros médicos, secretos comerciales, etc.

## Impacto de la exposición de datos sensibles

La exposición de datos sensibles puede tener consecuencias graves tanto para los individuos afectados como para las organizaciones responsables de proteger esos datos. Algunos de los posibles impactos incluyen:

- Robo de identidad: Los atacantes pueden utilizar la información expuesta para hacerse pasar por las víctimas y cometer fraudes financieros, abrir cuentas bancarias fraudulentas, solicitar préstamos o realizar compras no autorizadas.
- Daño financiero: Las víctimas pueden sufrir pérdidas financieras directas debido a transacciones fraudulentas o cargos no autorizados.
- Daño reputacional: Tanto los individuos como las organizaciones pueden sufrir daños a su reputación debido a la pérdida de confianza y la percepción de falta de seguridad.
- Sanciones legales y regulatorias: Las organizaciones pueden enfrentar multas y sanciones por incumplimiento de las leyes y regulaciones de protección de datos, como el GDPR o la HIPAA.
- Extorsión y chantaje: Los atacantes pueden amenazar con publicar o vender los datos expuestos a menos que se les pague un rescate.
- Discriminación: La información expuesta puede ser utilizada para discriminar a las personas en función de su raza, religión, orientación sexual, estado de salud u otras características protegidas.

## ¿Cómo ocurre la exposición de datos sensibles?

La exposición de datos sensibles puede ocurrir de varias maneras, incluyendo:

- Vulnerabilidades de seguridad en aplicaciones web: Ataques como SQLi, XSS o CSRF pueden permitir a los atacantes acceder a datos sensibles almacenados en bases de datos o sistemas de archivos.
- Configuración incorrecta de servidores y servicios: Servidores o servicios mal configurados pueden exponer datos sensibles a través de puertos abiertos, directorios accesibles o falta de autenticación.
- Pérdida o robo de dispositivos: Dispositivos como laptops, teléfonos móviles o unidades USB que contienen datos sensibles pueden ser perdidos o robados, lo que permite a los atacantes acceder a la información.
- Ingeniería social: Los atacantes pueden utilizar técnicas de manipulación psicológica para engañar a los empleados o usuarios para que revelen información confidencial o les proporcionen acceso a sistemas o datos sensibles.
- Errores humanos: Los empleados o usuarios pueden accidentalmente enviar datos sensibles a destinatarios equivocados, dejar documentos confidenciales en lugares públicos o descargar archivos adjuntos maliciosos.

## Mitigación de exposición de datos sensibles

Laravel proporciona varias herramientas y prácticas recomendadas para ayudar a mitigar la exposición de datos sensibles:

### Cifrado de datos

Utilizar el sistema de cifrado de Laravel para proteger datos sensibles en reposo. Laravel utiliza algoritmos de cifrado fuertes y seguros para proteger los datos mediante el facade Crypt ([ver la documentación oficial de Laravel Encryption](https://laravel.com/docs/11.x/encryption)).

```php
# SecurityController.php

use Illuminate\Support\Facades\Crypt;

...

$encryptedData = Crypt::encryptString($sensitiveData);
$decryptedData = Crypt::decryptString($encryptedData);

```

### Hashing de contraseñas

Nunca hay que almacenar contraseñas en texto plano. Se debe utilizar el facade Hash de Laravel para almacenar las contraseñas de forma segura ([ver la documentación oficial de Laravel Hash](https://laravel.com/docs/11.x/hashing)).

```php
# RegisterController.php

use Illuminate\Support\Facades\Hash;

...

User::create([
    'name' => 'John Doe',
    'email' => 'john-doe@example.com',
    'password' => Hash::make('P@ssword-s3c=r3'), 
]);

```

Mediante el mismo facade Hash se puede realizar la verificación de la contraseña de forma segura.

```php
# LoginController.php

use Illuminate\Support\Facades\Hash;

...

$password = 'P@ssword-s3c=r3';
$user = User::where('email', 'john-doe@example.com')->first();

if (Hash::check($password, $user->password)) {
    // La contraseña es correcta, permitir el acceso al usuario
}

```

### Validación y sanitización de entrada

Validar y sanitizar todas las entradas del usuario para prevenir ataques que podrían llevar a la exposición de datos sensibles ([ver documentacion de ASAWL - Fallos de validación de entrada](./fallos-validacion-de-entrada.md)).

### Control de acceso estricto

Implementar autenticación y autorización robustas para asegurarte de que solo los usuarios autorizados puedan acceder a datos sensibles. Utilizar roles y permisos para restringir el acceso a funcionalidades y datos específicos ([ver documentacion de ASAWL - Autenticación y autorización insegura](./autenticacion-autorizacion-insegura.md)).

### Gestión de errores segura

Configurar la aplicación para que no muestre información detallada sobre errores en producción. Esto evitará que los atacantes obtengan información valiosa sobre la estructura de la aplicación y posibles vulnerabilidades ([ver documentación de ASAWL - Configuración de seguridad incorrecta](./configuracion-seguridad-incorecta.md)).

### Actualizaciones de seguridad

Mantener Laravel y todas sus dependencias actualizadas para tener las últimas correcciones de seguridad y proteger la aplicación contra vulnerabilidades conocidas ([ver documentación de ASAWL - Componentes vulnerables y desactualizados](./componentes-vulnerables-desactualizados.md)).

### Auditoría y registro de actividad

Mantener un registro de las acciones realizadas por los usuarios, especialmente aquellas que involucran datos sensibles. Esto ayudará a detectar y responder a posibles incidentes de seguridad ([ver documentación oficial de ASAWL - Registro y monitoreo insuficiente](./registro-monitoreo-insuficiente.md)).

Adicional se detallan algunas recomendaciones de seguridad en exposición de datos sensibles:

- Minimización de datos: Recolectar y almacenar solo los datos estrictamente necesarios para el funcionamiento de la aplicación.
- Anonimización y seudonimización: Si es posible, anonimizar o seudonimizar los datos sensibles para reducir el riesgo en caso de exposición.
