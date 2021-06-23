# Convenciones de estilo

## Palabras "clave" del ingles aceptadas:
- find: Busca uno o mas elementos devuelve null, [] o el/los elementos.
- get: Devuelve el/los elementos en caso de no encontrarlos lanza una excepción.
- assert: Utilizado para definir guardas ya sea junto con early returns o lanzando excepciones.
- set: Define un valor en un objeto, su uso en entidades es desaconsejado.
- Interface: Para nombres de archivos e interfaces, según https://www.php-fig.org/bylaws/psr-naming-conventions/.
- Abstract: Para nombres de archivos y clases, según https://www.php-fig.org/bylaws/psr-naming-conventions/.
- Trait: Para nombres de archivos y Traits, según https://www.php-fig.org/bylaws/psr-naming-conventions/.
- Value object
- Exception: Para nombres de archivos y clases (como sufijo del nombre de la clase).
- Test: Para nombres de archivos y clases (como sufijo del nombre de la clase).
- Handler: Para nombres de archivos y clases (como sufijo del nombre de la clase).
- Middleware: Para nombres de archivos y clases (como sufijo del nombre de la clase).

En entidades, NO getters pero si fuesen necesarios, no utilizar la palabra get, simplemente el nombre del atributo a devolver.

## "Traducciones"
- add -> agregar
- remove -> remover
- delete -> eliminar
- by -> por
- domain -> dominio
- infrastructure -> infraestructura
- presentation -> presentacion
- application -> aplicacion
- use case -> caso de uso
