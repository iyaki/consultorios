<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Consultorios\RESTFramework\Fixtures\DummyDTO;
use Consultorios\RESTFramework\Fixtures\DummyTransformer;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ResponseFactory as DiactorosResponseFactory;
use League\Fractal\TransformerAbstract;
use PHPUnit\Framework\TestCase;

final class ResponseFactoryTest extends TestCase
{
    public function testConstructWithInvalidTransformers(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid transformer: stdClass');

        new ResponseFactory(
            new DiactorosResponseFactory(),
            [
                (new class() {})::class => (new \stdClass())::class,
            ]
        );
    }

    public function testConstructWithoutTransformers(): void
    {
        $webAppResponseFactory = new ResponseFactory(
            new DiactorosResponseFactory(),
            []
        );

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('There is no transformer configured for: stdClass');

        $webAppResponseFactory->createResponseFromItem(
            (object) []
        );

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('There is no transformer configured for: stdClass');

        $webAppResponseFactory->createResponseFromCollection([
            (object) []
        ]);
    }

    public function testCreateResponseFromItemResource(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $response = $webAppResponseFactory->createResponseFromItem(
            new DummyDTO(':D')
        );

        $this->assertJsonStringEqualsJsonString(
            '{"data":{"property":":D"}}',
            (string) $response->getBody()
        );
    }

    public function testCreateResponseFromItemResourceWithoutTransformer(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('There is no transformer configured for: stdClass');

        $webAppResponseFactory->createResponseFromItem(
            (object) []
        );
    }

    public function testCreateResponseFromItemNullResource(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $response = $webAppResponseFactory->createResponseFromItem(null);

        $this->assertJsonStringEqualsJsonString(
            '{"data": null}',
            (string) $response->getBody()
        );
    }

    public function testCreateResponseFromCollectionResource(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $response = $webAppResponseFactory->createResponseFromCollection([
            new DummyDTO(':D'),
            new DummyDTO(':c'),
        ]);

        $this->assertJsonStringEqualsJsonString(
            '{"data":[{"property":":D"},{"property":":c"}]}',
            (string) $response->getBody()
        );
    }

    public function testCreateResponseFromCollectionResourceWithoutTransformer(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('There is no transformer configured for: stdClass');

        $webAppResponseFactory->createResponseFromCollection([
            (object) [],
        ]);
    }

    public function testCreateResponseFromCollectionEmpty(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $response = $webAppResponseFactory->createResponseFromCollection([]);

        $this->assertJsonStringEqualsJsonString(
            '{"data": []}',
            (string) $response->getBody()
        );
    }

    public function testCreateResponseFromCollectionThrowable(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Multiple errors are not allowed');

        $webAppResponseFactory->createResponseFromCollection([new \Exception()]);
    }

    private function getWebAppResponseFactory(): ResponseFactory
    {
        return new ResponseFactory(
            new DiactorosResponseFactory(),
            [
                DummyDTO::class => DummyTransformer::class,
                (new class() {})::class => (new class() extends TransformerAbstract {})::class,
            ]
        );
    }
}
