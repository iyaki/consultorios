# Consultorios - Backend

## Development setup

```shell
composer install
```

## Versioando de la BD

Para gestionar el versionado de la base de datos se utiliza [Doctrine Migrations](https://www.doctrine-project.org/projects/migrations.html).

Para actualizar la BD debe ejecutarse:

```shell
doctrine-migrations migrations:migrate
```

## Documentation compilation

```shell
daux generate -s docs/funcional/ -d public/docs
```
