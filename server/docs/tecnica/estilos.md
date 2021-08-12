# Convenciones de estilo

## Palabras "clave" estandarizadas:
- find: Busca uno o mas elementos. Devuelve null, [] o el/los elementos.
- get: Devuelve el/los elementos en caso de no encontrarlos lanza una excepción.
- assert: Utilizado para definir guardas ya sea junto con early returns o lanzando excepciones.
- set: Define un valor en un objeto, su uso en entidades es desaconsejado.
- Interface: Para nombres de archivos e interfaces, según https://www.php-fig.org/bylaws/psr-naming-conventions/.
- Abstract: Para nombres de archivos y clases, según https://www.php-fig.org/bylaws/psr-naming-conventions/.
- Trait: Para nombres de archivos y Traits, según https://www.php-fig.org/bylaws/psr-naming-conventions/.
- Value object
- Exception: Para nombres de archivos y clases que representan una situacíon excepcional en el flujo del programa (como sufijo del nombre de la clase).
- Test: Para nombres de archivos y clases de pruebas unitarias (como sufijo del nombre de la clase).
- Handler: Para nombres de archivos y clases de controladores que manejan requests o eventos (como sufijo del nombre de la clase).
- Middleware: Para nombres de archivos y clases (como sufijo del nombre de la clase).
- Factory: Para nombres de archivos y clases cuya función sea crear instnacias de otras clases (como sufijo del nombre de la clase).
- Transformer: Para nombres de archivos y clases cuya función es convertir un objeto en otro tipo de expresión (tradicionalemnte json para utilizar como repsuesta de las APIs).
- Container: Contenedor utilizable para obtener instancias de servicios, abstrayendo los detalles de su creación.

## Métodos "obligatorios"
### Repositorios:
- crearId(): Id
- get(Id $id): Entity
- findBy($criteria): Entity[]
- add(Entity $entity): void
- remove(Id $id): void

## Reglas
### Entidades
- NO usar getters pero si fuesen necesarios, no utilizar la palabra get, simplemente el nombre del atributo a devolver.
- Todos los métodos y propiedades deben estar en español (salvo, tal vez, los getters).

### Implementaciones de Infraestructura
- Asumiendo que tenemos la interfaz `ServicioInterface` sus implementaciones deberan seguir el siguiente patrón para sus nombres: `Servicio<Implementación>`, por ejemplo: ServicioApiExterna.


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
