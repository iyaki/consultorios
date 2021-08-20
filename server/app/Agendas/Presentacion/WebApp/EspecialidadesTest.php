<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\CasosDeUso\Especialidades;
use Consultorio\Agendas\Dominio\EspecialidadRepositoryInterface;
use Consultorio\Agendas\Infraestructura\Presentacion\WebApp\ResponseFactoryAgendasFractal;
use Consultorio\Core\Aplicacion\UnitOfWorkInterface;
use GuzzleHttp\Client;
use Laminas\Diactoros\Request;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use PHPUnit\Framework\TestCase;

final class EspecialidadesTest extends TestCase
{
    private const URI_PATH = 'agendas/webapp/especialidades';

    private const NOMBRE_ORIGINAL = 'Especialidad de prueba API';

    private const NOMBRE_MODIFICADO = 'Especialidad de prueba API modificado';

    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://webserver/'
        ]);
    }

    public function testPostOk(): string
    {
        $response = $this->client->post(
            self::URI_PATH,
            $this->requestOptions([
                'data' => [
                    'nombre' => self::NOMBRE_ORIGINAL,
                ],
            ])
        );

        $parsedResponse = json_decode((string) $response->getBody());

        $this->assertSame(201, $response->getStatusCode());
        $this->assertIsString($parsedResponse->data->id);
        $this->assertSame(self::NOMBRE_ORIGINAL, $parsedResponse->data->nombre);

        return $parsedResponse->data->id;
    }

    /**
     * @depends testPostOk
     */
    public function testGetOk(string $id): string
    {
        $response = $this->client->get(self::URI_PATH);

        $parsedResponse = json_decode((string) $response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertIsArray($parsedResponse['data']);
        $this->assertContains(
            [
                'id' => $id,
                'nombre' => self::NOMBRE_ORIGINAL,
            ],
            $parsedResponse['data']
        );

        return $id;
    }

    /**
     * @depends testGetOk
     */
    public function testPatchOk(string $id): string
    {
        $response = $this->client->patch(
            self::URI_PATH . '/' . $id,
            $this->requestOptions([
                'data' => [
                    'nombre' => self::NOMBRE_MODIFICADO,
                ]
            ])
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'data' => [
                    'id' => $id,
                    'nombre' => self::NOMBRE_MODIFICADO,
                ],
            ]),
            (string) $response->getBody()
        );

        $parsedResponseGet = json_decode(
            (string) $this->client
                ->get(self::URI_PATH)
                ->getBody(),
            true
        );
        $this->assertNotContains(
            [
                'id' => $id,
                'nombre' => self::NOMBRE_ORIGINAL,
            ],
            $parsedResponseGet['data']
        );
        $this->assertContains(
            [
                'id' => $id,
                'nombre' => self::NOMBRE_MODIFICADO,
            ],
            $parsedResponseGet['data']
        );

        return $id;
    }

    /**
     * @depends testPatchOk
     */
    public function testDeleteOk(string $id): void
    {
        $response = $this->client->delete(self::URI_PATH . '/' . $id);

        $parsedResponse = json_decode((string) $response->getBody());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(null, $parsedResponse->data);

        $parsedResponseGet = json_decode(
            (string) $this->client
                ->get(self::URI_PATH)
                ->getBody(),
            true
        );
        $this->assertNotContains(
            [
                'id' => $id,
                'nombre' => self::NOMBRE_ORIGINAL,
            ],
            $parsedResponseGet['data']
        );
        $this->assertNotContains(
            [
                'id' => $id,
                'nombre' => self::NOMBRE_MODIFICADO,
            ],
            $parsedResponseGet['data']
        );
    }

    private function requestOptions(?array $body): array
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ];

        if ($body !== null) {
            $options['body'] = json_encode($body);
        }
        return $options;
    }
}
