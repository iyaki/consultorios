# ORM

## Envoltura para Doctrine ORM 2, Doctrine Migrations y PDO_MySQL

### Instalación

1 - Configurar composer para aceptar repositorios locales:

  composer.json

```json
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

El campo `url` debe apuntar al path contenedor de ORM (`packages/php/`)

2 - Ejecutar en una shell:

```shell
composer require consultorios/orm
```

Tambien se sugiere instalar `doctrine/orm` para evitar *weak dependencies* y
`ramsey/uuid` para la generaciín de UUID para las entidades:

```shell
composer require \
  doctrine/orm \
  ramsey/uuid
```

Documentación útil:

- [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html)
- [Doctrine Migrations](https://www.doctrine-project.org/projects/migrations.html)
- [PDO_MySQL](https://www.php.net/manual/en/ref.pdo-mysql.php)
- [Ramsey UUID](https://uuid.ramsey.dev/en/stable/)

### Uso

```php
$dbSettings = new DatabaseConnectionSettings(
    (string) getenv('DB_HOST'),
    (string) getenv('DB_DATABASE'),
    (string) getenv('DB_USER'),
    (string) getenv('DB_PASSWORD'),
);

$devMode = true;

$orm = new ORM(
    $dbSettings,
    [__DIR__ . '/../config/mappings'],
    $devMode
);
```

Para configurar el CLI de Doctrine:

```php
# cli-config.php
<?php

declare(strict_types=1);

use function Consultorios\ORM\getDoctrineCliConfig;

return getDoctrineCliConfig(__DIR__);
```
