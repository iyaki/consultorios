# RESTFramework

## Envoltura para simplificar el desarrollo de APIs REST utilizando [mezzio](https://docs.mezzio.dev/)

### Instalación

1. Configurar composer para aceptar repositorios locales:

  composer.json

```
"repositories": [
    {
      "type": "path",
      "url": "../*",
      "only": ["consultorios/*"],
      "options": {
        "symlink": true
      }
    }
  ]
```

El campo `url` debe apuntar al path contenedor de RESTFramework (`packages/php/`)


2. Ejecutar en una shell:
   
```shell
composer require consultorios/rest-framework
```

Adicionalmente se sugiere instalar `zircote/swagger-php` para evitar "weak dependencies a la hora de documentar la api:

```shell
composer require zircote/swagger-php
```

Documentación útil:
- [OpenAPI](https://swagger.io/docs/specification/about/)
- [swagger-php](https://swagger.io/docs/specification/about/)

### Uso

```php
(new Application(
    routesConfigurator: require __DIR__ . '/../config/routes.php',
    documentationPath: __DIR__ . '/../app/',
    uriBasePath: '/webapp/'
))->run();
```
