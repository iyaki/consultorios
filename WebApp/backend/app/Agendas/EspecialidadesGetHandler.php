<?php

declare(strict_types=1);

namespace Consultorios\WebApp\Agendas;

use Consultorios\Core\Agendas\Domain\Especialidad;
use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\RESTFramework\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EspecialidadesGetHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ResponseFactory $responseFactory,
        private readonly Especialidades $especialidades,
    ) {
    }

    /**
     *  @OA\Get(
     *      path="/webapp/agendas/especialidades",
     *      operationId="listarEspecialidades",
     *      summary="Lista las especialidades registradas",
     *      description="Expone una lista de todas las especialidades registradas ordenadas alfabeticamente.",
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
        $especialidades = $this->especialidades->getAll();
        usort(
            $especialidades,
            static fn (Especialidad $e1, Especialidad $e2): int => $e1->nombre() <=> $e2->nombre()
        );
        return $this->responseFactory->createResponseFromCollection($especialidades);
    }
}
