# Bitácora
<!-- Bitácora del capitán, fecha estelar 1513.1 -->

Este proyecto comienzs cómo una prueba de concepto de un sistema desarrollado con DDD y buenas prácticas de POO como guía. El objetivo fina les tener un pequeño sistema altamente desacoplado (al punto de poder eliminar modulos enteros sin ninguna consecuencia para el resto de la aplicación).

Para lograr este nivel de desacoplamiento se separó la aplicación en módulos y cada módulo en las siguientes capas:
- Casos De Uso: Capa de orquestación del dominio.
- Dominio: Lógica de negocio de la aplicación.
- Infraestructura: Capa donde encapsular todos los detalles de implementación relacionados a las dependencias de la aplicación (I/O, bibliotecas, APIs externas, etc).
- Presentación: Capa donde ubicar todos los conceptos propios de las formas de interactuar con la aplicación. (El plan iniciar es contar con 2 APIs REST, una dedicada exclusivamente al frontend y otra "generica" siguiendo la especificación JSON:API).

Se decidió utilizar las siguientes tecnologías:
- PHP 8.0 como lenguaje para el backend debido a que es el lenguaje que mejor manejo en la actualidad y que posee un gran parecido con otros lenguajes orientados a objetos.
- Mezzio (con FastRoute) cómo framework web debido a la alta compatibilidad con distintos componentes y la gran comunidad que posee detras al tratarse de la continuación del framework de Zend (Zend Expressive).
- Doctrine ORM para simplificar la interacción con la BD y abstraerse de la misma.
- Doctrine Migrations para versionar la BD.
- Ramsey UUID par ala generación de UUIDs.
- Symfony Cache cómo cache para Doctrine ORM.
- League Fractal para formar las respuestas de las distintas APIs.
- PSR 7, 11, 15, 17.

Posteriormente se creó una envoltura sobre la configuración de rutas de Mezzio ya que el uso factories para la creación de Handlers y posteriormente de arrays para asociar clases y las factories. Esta nueva capa permite definir los "factories" directamente como argumento a la hora de definir la ruta (mediante el uso de funciones anónimas).

Inicialmente se decidió abstraer completamente la capa de Presentación de la capa de Dominio definiendo DTOs en la capa de Casos de Uso y utilizando unicamente estos DTOs en la capa de Presentación en lugar de las entidades pero este esquema no resultó práctico ni útil por lo que se procedió a eliminar estos DTOs de las entidades y se modifican los casos de uso para que reciban valores "primitivos" (O a lo sumo value objects) y devuelvan entidades.

Se decidió incluir los tests unitarios junto con el código que se prueba (dentro de app/ y sin estar separados en una carpeta particular), esto debido a la influecia de golang y su "estandar" de mantener todos los componentes relacionados lo mas cerca posible.
