<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

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

        $manager = new Manager();

        $manager->setSerializer(
            $resource instanceof \Throwable
            ? new JsendErrorSerializer()
            : new JsendResourceSerializer()
        );

        $body = $manager
            ->createData(
                $this->getItemTransformer($resource)
            )
            ->toJson()
        ;

        $response->getBody()->write($body);

        return $response;
    }

    /**
     * @param object[] $resources
     */
    public function createResponseFromCollection(array $resources, int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code, $reasonPhrase);

        $manager = new Manager();

        $manager->setSerializer(
            reset($resources) instanceof \Throwable
            ? new JsendErrorSerializer()
            : new JsendResourceSerializer()
        );

        $body = $manager
            ->createData(
                $this->getCollectionTransformer($resources)
            )
            ->toJson()
        ;

        $response->getBody()->write($body);

        return $response;
    }

    private function getItemTransformer(?object $resource): Item
    {
        if ($resource === null) {
            return new Item($resource, $this->getVoidTransformer());
        }
        foreach ($this->transformers as $resourceFrom => $transformer) {
            if ($resource instanceof $resourceFrom) {
                return new Item($resource, new $transformer());
            }
        }
        throw new \UnexpectedValueException('There is no transformer configured for ' . $resource::class);
    }

    /**
     * @param object[] $resources
     */
    private function getCollectionTransformer(array $resources): Collection
    {
        if ($resources === []) {
            return new Collection($resources, $this->getVoidTransformer());
        }

        $resource = reset($resources);
        foreach ($this->transformers as $resourceFrom => $transformer) {
            if ($resource instanceof $resourceFrom) {
                return new Collection($resources, new $transformer());
            }
        }
        throw new \UnexpectedValueException('There is no transformer configured for ' . $resource::class);
    }

    private function getVoidTransformer(): callable
    {
        return fn (): array => [];
    }
}
