<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;
use League\Fractal\Resource\ResourceAbstract;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Clase base utilizada para convertir entidades en respuestas JSON utilizadas por las APIs REST de la aplicación.
 */
abstract class AbstractWebAppResponseFactoryFractal implements WebAppResponseFactoryInterface
{
    /**
     * @var array<string, class-string>
     */
    private const BASE_TRANSFORMERS = [
        \Throwable::class => ThrowableTransformer::class,
    ];

    protected array $transformers = [];

    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {
        $this->transformers = array_merge(self::BASE_TRANSFORMERS, $this->transformers);
    }

    public function createResponseFromItem(?object $resource, int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code, $reasonPhrase);

        $item = (
            $resource === null
            ? new NullResource()
            : $this->getItem($resource)
        );

        $response->getBody()->write($this->transformResourceToJson($item));

        return $response;
    }

    /**
     * @param object[] $resources
     */
    public function createResponseFromCollection(array $resources, int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code, $reasonPhrase);

        $collection = $this->getCollection($resources);

        $response->getBody()->write($this->transformResourceToJson($collection));

        return $response;
    }

    private function getItem(object $resource): Item
    {
        foreach ($this->transformers as $resourceFrom => $transformer) {
            if ($resource instanceof $resourceFrom) {
                return new Item($resource, new $transformer());
            }
        }
        throw new \UnexpectedValueException('There is no transformer configured for ' . $resource::class);
    }

    private function transformResourceToJson(ResourceAbstract $resource): string
    {
        return (new Manager())
            ->setSerializer(new WebAppSerializer())
            ->createData($resource)
            ->toJson()
        ;
    }

    /**
     * @param object[] $resources
     */
    private function getCollection(array $resources): Collection
    {
        if ($resources === []) {
            return new Collection([], fn () => []);
        }

        $resource = reset($resources);

        if ($resource instanceof \Throwable) {
            throw new \Exception(
                'El envío de multiples errores no esta permitido'
            );
        }

        foreach ($this->transformers as $resourceFrom => $transformer) {
            if ($resource instanceof $resourceFrom) {
                return new Collection($resources, new $transformer());
            }
        }

        throw new \UnexpectedValueException(
            'No hay transformer configurado para: ' . $resource::class
        );
    }
}
