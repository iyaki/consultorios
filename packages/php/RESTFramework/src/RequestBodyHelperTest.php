<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

final class RequestBodyHelperTest extends TestCase
{
    use RequestBodyHelper;

    public function testGetDataOk(): void
    {
        $data = $this->getData($this->request([
            'data' => [
                'prop1' => 'value1',
            ]
        ]));

        $this->assertSame($data->prop1, 'value1');
    }

    public function testGetDataNotOk(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid body data');

        $this->getData($this->request(['data' => null]));

    }

    public function testGetDataInvalidBody(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid body format');

        $this->getData($this->request(['asd']));

    }

    private function request(array $body): ServerRequest
    {
        $request = new ServerRequest(
            [],
            [],
            null,
            null,
            'php://memory'
        );

        $request->getBody()->write(
            json_encode($body)
        );

        return $request;
    }

    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    public function __sleep()
    {
        throw new \Exception('This class can\'t be serialized');
    }
}
