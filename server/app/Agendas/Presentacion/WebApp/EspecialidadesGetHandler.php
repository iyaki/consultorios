<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Core\Presentacion\WebApp\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesGetHandler implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private Especialidades $especialidades,
    ) {
    }

    /**
     *  @OA\Get(
     *      path="/agendas/webapp/especialidades",
     *      operationId="listarEspecialidades",
     *      summary="Lista las especialidades registradas",
     *      description="Expone una lista de todas las especialidades registradas actualmente.",
     *      tags={"Especialidades"},
     *      @OA\Response(
     *          response=200,
     *          description="Lista de especialidades.",
     *          @OA\JsonContent(
     *              type="object",
     *              nullable=false,
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  nullable=false,
     *                  uniqueItems=false,
     *                  @OA\Items(
     *                      ref="#/components/schemas/Especialidad",
     *                  ),
     *              ),
     *              required={"data"},
     *              additionalProperties=false,
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error inesperado.",
     *          @OA\JsonContent(
     *              type="object",
     *              nullable=false,
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  nullable=false,
     *                  ref="#/components/schemas/Error",
     *              ),
     *              required={"data"},
     *              additionalProperties=false,
     *          ),
     *      ),
     *  ),
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponseFromCollection(
            $this->especialidades->getAll()
        );
    }
}
