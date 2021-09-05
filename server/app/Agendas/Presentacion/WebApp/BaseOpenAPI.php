<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

/**
 *  @OA\Info(
 *      title="API Interna",
 *      version="0.1"
 *  ),
 *  @OA\Server(
 *      url=SERVER_HOST
 *  ),
 *  @OA\Schema(
 *      schema="Error",
 *      description="Error procesando la petición.",
 *      type="object",
 *      @OA\Property(
 *          property="message",
 *          description="Mensaje descriptivo del error ocurrido",
 *          type="string",
 *          nullable=false,
 *      ),
 *      @OA\Property(
 *          property="code",
 *          description="Código indentificador del error.",
 *          type="string",
 *          nullable=false,
 *          pattern="[0-9]+",
 *          default="0",
 *      ),
 *      @OA\Property(
 *          property="file",
 *          description="Archivo donde ocurrió el error.",
 *          type="string",
 *          nullable=false,
 *      ),
 *      @OA\Property(
 *          property="line",
 *          description="Númeor de línea del archivo donde ocurrió el error.",
 *          type="number",
 *          nullable=false,
 *          format="int64",
 *      ),
 *      @OA\Property(
 *          property="trace",
 *          description="Lista de llamadas internas de la aplicación que finalizaron en el error.",
 *          nullable=false,
 *          type="string",
 *      ),
 *      required={"message", "code", "file", "line", "trace"},
 *  ),
 */
