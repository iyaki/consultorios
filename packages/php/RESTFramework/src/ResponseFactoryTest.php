<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Exception;
use Laminas\Diactoros\Response\TextResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;

final class ResponseFactoryTest extends TestCase
{
    /**
     * @var string
     */
    private const EXCEPTION_MESSAGE = 'Soy un mensaje de error';

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

    public function testCreateResponseFromItemNullResource(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $response = $webAppResponseFactory->createResponseFromItem(null);

        $this->assertJsonStringEqualsJsonString(
            '{"data": null}',
            (string) $response->getBody()
        );
    }

    public function testCreateResponseFromItemThrowable(): void
    {
        $webAppResponseFactory = $this->getWebAppResponseFactory();

        $response = $webAppResponseFactory->createResponseFromItem(
            new Exception(self::EXCEPTION_MESSAGE)
        );

        $decodedResponseBody = json_decode(
            (string) $response->getBody(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );
        $this->assertSame(
            self::EXCEPTION_MESSAGE,
            $decodedResponseBody->data->message
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

        $this->expectException(Exception::class);

        $webAppResponseFactory->createResponseFromCollection([new Exception()]);
    }

    private function getWebAppResponseFactory(): ResponseFactory
    {
        $psrResponseFactory = $this->createStub(ResponseFactoryInterface::class);
        $psrResponseFactory->method('createResponse')
            ->willReturnCallback(static fn(int $code = 200): TextResponse => new TextResponse('', $code))
        ;

        return new ResponseFactory(
            $psrResponseFactory,
            [
                DummyDTO::class => DummyTransformer::class,
            ]
        );
    }
}
