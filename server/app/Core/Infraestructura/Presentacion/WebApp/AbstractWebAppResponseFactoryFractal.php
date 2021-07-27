<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion\WebApp;

use Consultorio\Core\Presentacion\WebApp\WebAppResponseFactoryInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractWebAppResponseFactoryFractal implements WebAppResponseFactoryInterface
{
    /**
     * @var array<string, class-string>
     */
    protected array $transformers = [
        \Throwable::class => ThrowableTransformer::class,
    ];

    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function createResponseFromItem(object $resource, int $code, string $reasonPhrase = ''): ResponseInterface
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
                $this->getTransformer($resource)
            )
            ->toJson()
        ;

        $response->getBody()->write($body);

        return $response;
    }

    private function getTransformer(object $resource): Item
    {
        foreach ($this->transformers as $resourceFrom => $transformer) {
            if ($resource instanceof $resourceFrom) {
                /** @psalm-suppress MixedMethodCall */
                return new Item($resource, new $transformer());
            }
        }
        throw new \UnexpectedValueException('There is no transformer configured for ' . $resource::class);
    }
}
