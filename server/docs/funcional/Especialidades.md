Dentro del sistema especialidad se refiere a una especialidad médica.

# Índice
- [Índice](#índice)
- [Casos de uso](#casos-de-uso)
  - [Registro de especialidades](#registro-de-especialidades)
    - [Dependencias](#dependencias)
    - [Precondiciones](#precondiciones)
    - [Descripción](#descripción)
    - [Excepciones](#excepciones)
    - [Comentarios](#comentarios)
  - [Edición de registros de especialidades](#edición-de-registros-de-especialidades)
    - [Dependencias](#dependencias-1)
    - [Precondiciones](#precondiciones-1)
    - [Descripción](#descripción-1)
    - [Excepciones](#excepciones-1)
    - [Comentarios](#comentarios-1)
  - [Eliminación de registros de especialidades](#eliminación-de-registros-de-especialidades)
    - [Dependencias](#dependencias-2)
    - [Precondiciones](#precondiciones-2)
    - [Descripción](#descripción-2)
    - [Excepciones](#excepciones-2)
    - [Comentarios](#comentarios-2)

# Casos de uso

## Registro de especialidades

### Dependencias

- Ninguna.

### Precondiciones

- Ninguna.

### Descripción

Los usuarios deben poder registrar especialidades médicas en el sistema
brindando un nombre para la misma con el fin de, posteriormente, poder
configurar agendas médicas.

### Excepciones

- No se debe permitir el registro de especialidades con un nombre vacío.
- No se debe permitir el registro de especialidades con un nombre compuesto
integramente de caracteres de espaciado (espacios o tabs).
- No se debe permitir el registro de especialidades con nombres repetidos.

### Comentarios

- Ninguno.

## Edición de registros de especialidades

### Dependencias

- [Registro de especialidades](#registro-de-especialidades).

### Precondiciones

- Contar con registros de especialidades.

### Descripción

Los usuarios deben poder editar el nombre de los registros de especialidades
médicas en el sistema brindando un nuevo nombre.

### Excepciones

- No se debe permitir el registro de especialidades con un nombre vacío.
- No se debe permitir el registro de especialidades con un nombre compuesto
integramente de caracteres de espaciado (espacios o tabs).
- No se debe permitir el registro de especialidades con nombres repetidos.

### Comentarios

- Ninguno.

## Eliminación de registros de especialidades

### Dependencias

- [Registro de especialidades](#registro-de-especialidades).

### Precondiciones

- Contar con registros de especialidades.

### Descripción

Los usuarios deben poder eliminar registros de especialidades médicas.

### Excepciones

- Ninguna.

### Comentarios

- Ninguno.
