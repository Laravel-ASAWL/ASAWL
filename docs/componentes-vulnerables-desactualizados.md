# Componentes Vulnerables y Desactualizados

## ¿Qué son los Componentes Vulnerables y Desactualizados?

En el contexto de Laravel, los componentes vulnerables y desactualizados se refieren a cualquier biblioteca, paquete o dependencia de terceros que se utiliza en una aplicación Laravel y que contiene fallos de seguridad conocidos o que ya no recibe actualizaciones y parches de seguridad por parte de sus mantenedores.

## Impacto de los Componentes Vulnerables y Desactualizados

El uso de componentes vulnerables y desactualizados en una aplicación Laravel puede tener un impacto significativo en su seguridad, incluyendo:

- Explotación de vulnerabilidades conocidas: Los atacantes pueden aprovechar las vulnerabilidades publicadas en componentes desactualizados para comprometer la aplicación, robar datos sensibles, ejecutar código malicioso o incluso tomar el control del servidor.
- Acceso no autorizado: Las vulnerabilidades en los componentes pueden permitir a los atacantes eludir los mecanismos de autenticación y autorización, obteniendo acceso a áreas restringidas de la aplicación o a datos confidenciales.
- Ataques de denegación de servicio (DoS): Algunas vulnerabilidades pueden ser explotadas para causar inestabilidad en la aplicación o consumir recursos excesivos, lo que puede resultar en una denegación de servicio para los usuarios legítimos.
- Pérdida de reputación: Si una aplicación Laravel es comprometida debido a componentes vulnerables, puede dañar la reputación de la organización y generar desconfianza entre los usuarios.

## ¿Cómo ocurren los Componentes Vulnerables y Desactualizados?

Los componentes vulnerables y desactualizados pueden aparecer en una aplicación Laravel de varias maneras:

- Uso de dependencias de terceros: Laravel fomenta el uso de paquetes y bibliotecas de terceros para acelerar el desarrollo. Sin embargo, es importante asegurarse de que estas dependencias se mantengan actualizadas y se apliquen los parches de seguridad de manera oportuna.
- Falta de seguimiento de las dependencias: Los desarrolladores pueden no ser conscientes de todas las dependencias indirectas o "anidadas" que se incluyen en su aplicación, lo que dificulta el seguimiento de las actualizaciones y vulnerabilidades.
- Presión para entregar funcionalidades rápidamente: La necesidad de entregar nuevas funcionalidades rápidamente puede llevar a los desarrolladores a utilizar componentes sin realizar una evaluación de seguridad adecuada o a retrasar la aplicación de actualizaciones.

## Mitigación de componentes vulnerables y desactualizados

Laravel permite mitigar esta vulnerabilidad de la siguiente manera:

- Composer permite actualizar regularmente las dependencias de Laravel y de terceros. Esto asegura la obtener las últimas correcciones de seguridad y mejoras de rendimiento.

```bash
# terminal

composer update

Loading composer repositories with package information
Updating dependencies

Nothing to modify in lock file
Installing dependencies from lock file (including require-dev)

Nothing to install, update or remove   

78 packages you are using are looking for funding.

No security vulnerability advisories found.

```

- Herramientas como security-checker permite identificar vulnerabilidades conocidas en las dependencias. La ejecución de esta herramienta periódicamente permite actualizar o reemplaza los componentes vulnerables (GitHub, 2024b).

```bash
# terminal

composer require --dev enlightn/security-checker

php vendor/bin/security-checker security:check composer.lock

[OK] 0 packages have known vulnerabilities

```


- Herramientas como security-advisories permite mantenerse informado sobre las últimas vulnerabilidades de seguridad en los componentes que utilizan las aplicación web (GitHub, 2024c).

```bash
# terminal

composer require --dev roave/security-advisories:dev-latest

composer update --dry-run roave/security-advisories

No security vulnerability advisories found.

```
