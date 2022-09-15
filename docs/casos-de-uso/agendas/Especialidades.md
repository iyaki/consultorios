# 01 - Especialidades

Dentro del sistema especialidad se refiere a una especialidad médica.

## Índice

- [01 - Especialidades](#01---especialidades)
  - [Índice](#índice)
  - [Casos de uso](#casos-de-uso)
    - [01.01 - Registro de especialidades](#0101---registro-de-especialidades)
      - [01.01 - Dependencias](#0101---dependencias)
      - [01.01 - Precondiciones](#0101---precondiciones)
      - [01.01 - Descripción](#0101---descripción)
      - [01.01 - Excepciones](#0101---excepciones)
      - [01.01 - Comentarios](#0101---comentarios)
    - [01.02 - Edición de registros de especialidades](#0102---edición-de-registros-de-especialidades)
      - [01.02 - Dependencias](#0102---dependencias)
      - [01.02 - Precondiciones](#0102---precondiciones)
      - [01.02 - Descripción](#0102---descripción)
      - [01.02 - Excepciones](#0102---excepciones)
      - [01.02 - Comentarios](#0102---comentarios)
    - [01.03 - Eliminación de registros de especialidades](#0103---eliminación-de-registros-de-especialidades)
      - [01.03 - Dependencias](#0103---dependencias)
      - [01.03 - Precondiciones](#0103---precondiciones)
      - [01.03 - Descripción](#0103---descripción)
      - [01.03 - Excepciones](#0103---excepciones)
      - [01.03 - Comentarios](#0103---comentarios)

## Casos de uso

### 01.01 - Registro de especialidades

#### 01.01 - Dependencias

- Ninguna.

#### 01.01 - Precondiciones

- Ninguna.

#### 01.01 - Descripción

Los usuarios deben poder registrar especialidades médicas en el sistema
brindando un nombre para la misma con el fin de, posteriormente, poder
configurar agendas médicas.

#### 01.01 - Excepciones

- No se debe permitir el registro de especialidades con un nombre vacío.
- No se debe permitir el registro de especialidades con un nombre compuesto
integramente de caracteres de espaciado (espacios o tabs).
- No se debe permitir el registro de especialidades con nombres repetidos.

#### 01.01 - Comentarios

- Ninguno.

### 01.02 - Edición de registros de especialidades

#### 01.02 - Dependencias

- [Registro de especialidades](#0101---registro-de-especialidades).

#### 01.02 - Precondiciones

- Contar con registros de especialidades.

#### 01.02 - Descripción

Los usuarios deben poder editar el nombre de los registros de especialidades
médicas en el sistema brindando un nuevo nombre.

#### 01.02 - Excepciones

- No se debe permitir el registro de especialidades con un nombre vacío.
- No se debe permitir el registro de especialidades con un nombre compuesto
integramente de caracteres de espaciado (espacios o tabs).
- No se debe permitir el registro de especialidades con nombres repetidos.

#### 01.02 - Comentarios

- Ninguno.

### 01.03 - Eliminación de registros de especialidades

#### 01.03 - Dependencias

- [Registro de especialidades](#0101---registro-de-especialidades).

#### 01.03 - Precondiciones

- Contar con registros de especialidades.

#### 01.03 - Descripción

Los usuarios deben poder eliminar registros de especialidades médicas.

#### 01.03 - Excepciones

- Ninguna.

#### 01.03 - Comentarios

- Ninguno.
