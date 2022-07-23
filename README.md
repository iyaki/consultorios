# Consultorios

Pet project for proofs of concept about system design
Proyecto "de juguete" para realizar pruebas de concepto de diseño de sistemas
y uso de tecnologías.

## Development setup

Para iniciar el entorno de desarrollo debe ejecutarse:

```shell
deployment/dev/up
```

Esto iniciará 2 containers correspondientes a:

- app (php & node)
- MariaDB

El container "app" esta diseñado para trabajar directamente dentro del mismo
(esto puede hacerse con la extension
[Remote - Containers](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)
del [Visual Studio Code](https://code.visualstudio.com/)) y cuenta con
multiples utilidades para el desarrollo y analisis del código generado.
