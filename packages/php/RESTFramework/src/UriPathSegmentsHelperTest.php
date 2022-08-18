<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

final class UriPathSegmentsHelperTest extends TestCase
{
    use UriPathSegmentsHelper;

    private const BASE_URI = 'https://localhost:8080/leapp/context/elrecurso/';

    private const ID = '42';

    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    public function __sleep()
    {
        throw new \Exception("This class can't be serialized");
    }

    public function testFindIdUriWithId(): void
    {
        $request = new ServerRequest(
            [],
            [],
            self::BASE_URI . self::ID
        );

        $this->assertSame(self::ID, $this->findId($request));
    }

    public function testFindIdUriWithoutId(): void
    {
        $request = new ServerRequest(
            [],
            [],
            self::BASE_URI
        );

        $this->assertNull($this->findId($request));
    }

    public function testGetIdUriWithId(): void
    {
        $request = new ServerRequest(
            [],
            [],
            self::BASE_URI . self::ID
        );

        $this->assertSame(self::ID, $this->getId($request));
    }

    public function testGetIdUriWithoutId(): void
    {
        $request = new ServerRequest(
            [],
            [],
            self::BASE_URI
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontró un ID en la URI');

        $this->getId($request);
    }
}
