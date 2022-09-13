<?php

declare(strict_types=1);

namespace Consultorios\Core\Agendas\UseCases;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\Domain\EspecialidadId;

/**
 * Servicio de casos de uso existentes para trabajar con especialidades.
 * Ya que las especialidades no cumplen una función en si mismas sino que son
 * utilizadas como parte de la lógica de las Citas las operaciones disponibles
 * en este servicio son las correspondientes a CRUD.
 *
 * @api
 */
interface EspecialidadesInterface
{
    /**
     * Devuelve todas las especialidades.
     *
     * @return Especialidad[]
     */
    public function getAll(): array;

    /**
     * Crea una nueva especialidad a partir de un nombre.
     *
     * @throws \Exception excepción lanzada al intentar crear una nueva
     * especialidad a partir de un nombre repetido.
     */
    public function crear(string $nombre): Especialidad;

    /**
     * Edita el nombre de una especialidad.
     *
     * @throws \Exception excepción lanzada al intentar editar una especialidad
     * con un nombre repetido.
     * @throws \UnexpectedValueException excepción lanzada al intentar editar
     * una especialidad inexistente.
     */
    public function editar(EspecialidadId $id, string $nombre): Especialidad;

    /**
     * Elimina una especialidad
     *
     * @throws \UnexpectedValueException excepción lanzada al intentar eliminar
     * una especialidad inexistente.
     */
    public function eliminar(EspecialidadId $id): void;

}
