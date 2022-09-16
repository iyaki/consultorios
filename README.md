# Consultorios

Proyecto "de juguete" para realizar pruebas de concepto de diseño de sistemas
y uso de tecnologías.

## Development setup

Para iniciar el entorno de desarrollo debe ejecutarse:

```shell
deploy/dev/up
```

Esto iniciará 2 containers correspondientes a:

- app (php & node)
- MariaDB

El container "app" esta diseñado para trabajar directamente dentro del mismo
(esto puede hacerse con la extension
[Remote - Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)
del [Visual Studio Code](https://code.visualstudio.com/)) y cuenta con
multiples utilidades para el desarrollo y analisis del código generado.

### Dependencias

- Docker
- Docker compose
- nodejs & npm
- Python
- C/C++ compiler

Para trabajar con Visual Studio Code se recomienda instalar la extensión `ms-vscode-remote.remote-containers`

## Scripts

Cada paquete contenido en este proyecto **DEBE** tener una carpeta `scripts/`
con las siguientes utilidades:

- check: Script para verificar la integridad y buenas practicas del paquete.
- setup: Script para inicializar el paquete y permitir trabajar en él.

Ademas la carpeta scripts puede contener:

- unit-test: Script para ejecutar test unitarios del paquete.
- serve: Script para lanzar un servidor de desarrollo.
