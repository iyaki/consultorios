<?php

declare(strict_types=1);

namespace Consultorios\RESTFramework;

use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

final class UriHelperTest extends TestCase
{
    use UriHelper;

    private const BASE_URI_WITHOUT_PORT = 'https://localhost';

    private const BASE_URI_WITH_PORT = self::BASE_URI_WITHOUT_PORT . ':8080';

    /**
     * @var string
     */
    private const URI = self::BASE_URI_WITH_PORT . '/leapp/context/elrecurso/';

    /**
     * @var string
     */
    private const ID = '42';

    public function testFindIdUriWithId(): void
    {
        $request = new ServerRequest(
            uri: self::URI . self::ID
        );

        $this->assertSame(self::ID, $this->findResourceIdFromPath($request));
    }

    public function testFindIdUriWithoutId(): void
    {
        $request = new ServerRequest(
            uri: self::URI
        );

        $this->assertNull($this->findResourceIdFromPath($request));
    }

    public function testGetIdUriWithId(): void
    {
        $request = new ServerRequest(
            uri: self::URI . self::ID
        );

        $this->assertSame(self::ID, $this->getResourceIdFromPath($request));
    }

    public function testGetIdUriWithoutId(): void
    {
        $request = new ServerRequest(
            uri: self::URI
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontró un ID en la URI');

        $this->getResourceIdFromPath($request);
    }

    public function testGetBaseUriWithPort(): void
    {
        $request = new ServerRequest(
            uri: self::BASE_URI_WITH_PORT . '/asd/asdf/asdf'
        );

        $baseUri = $this->getBaseUri($request);

        $this->assertSame(self::BASE_URI_WITH_PORT, $baseUri);
    }

    public function testGetBaseUriWithoutPort(): void
    {
        $request = new ServerRequest(
            uri: self::BASE_URI_WITHOUT_PORT . '/asd/asdf/asdf'
        );

        $baseUri = $this->getBaseUri($request);

        $this->assertSame(self::BASE_URI_WITHOUT_PORT, $baseUri);
    }
}
