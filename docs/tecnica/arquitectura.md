# Diseño y arquitectura de la aplicación [WIP]

Todo el código propio de la aplicación (exceptuando `public/index.php` que actua como bootstrap de la aplicación y entrypoint para los requests y por razones de seguridad se encuentra separado en otra carpeta, de forma tal que el servidor web no tenga acceso a nada fuera de `public/`) se encuetra dentro de `app/`, siguiendo la siguiente jerarquia:
- app/
  - <Módulo> (Equivalente a un Bounded context en DDD)
    <!-- - Aplicacion (Capa donde se alojan las clases inherentes al hecho de estar trabajando en una aplicación como podría ser, por ej, Mailer). -->
    - CasosDeUso (Capa que se encarga de orquestar las acciones del Dominio).
    <!-- - Config (Aqui se alojan las configuraciones especificas del módulo). -->
    - Dominio (Capa donde se diseñan e implementan las reglas del negocio, aquí se deben alojar: Entidades, Interfaces de repositorios, Value Objects y algunos servicios que resulten pertinentes).
    - Infraestructura (Aqui se implementan todas las interfaces de las demas capas que requieran de librerias o servicios externos o acceso al I/O del sistema).
      <!-- - Aplicacion -->
      - Dominio
      - Presentacion
    - Presentacion (Capa donde almacenar los handlers mediante los cuales los usuarios finales podrán interactuar con los casos de uso).
      - PublicAPI (Presentación utilizada para que terceros establezcan integraciones con el sistema. Sigue el estandar [JSON API](https://jsonapi.org/)).
      - WebApp (Presentación consumida únicamente por la interfaz web propia de la aplicación. Sigue el estandar [JSend](https://github.com/omniti-labs/jsend)).
        - Handlers
        - container.php
        - routes.php
