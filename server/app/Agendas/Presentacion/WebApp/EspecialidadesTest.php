<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class EspecialidadesTest extends TestCase
{
    /**
     * @var string
     */
    private const URI_PATH = 'agendas/webapp/especialidades';

    /**
     * @var string
     */
    private const NOMBRE_ORIGINAL = 'Especialidad de prueba API';

    /**
     * @var string
     */
    private const NOMBRE_MODIFICADO = 'Especialidad de prueba API modificado';

    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://webserver/',
            'http_errors' => false,
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

        $parsedResponse = json_decode((string) $response->getBody(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertSame(201, $response->getStatusCode());
        $this->assertIsString($parsedResponse->data->id);
        $this->assertSame(self::NOMBRE_ORIGINAL, $parsedResponse->data->nombre);

        return $parsedResponse->data->id;
    }

    /**
     * @depends testPostOk
     */
    public function testPostRepetido($id): string
    {
        $response = $this->client->post(
            self::URI_PATH,
            $this->requestOptions([
                'data' => [
                    'nombre' => self::NOMBRE_ORIGINAL,
                ],
            ])
        );

        $parsedResponse = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);

        $this->assertSame(500, $response->getStatusCode());
        $this->assertIsString($parsedResponse->data->message);

        return $id;
    }

    /**
     * @depends testPostRepetido
     */
    public function testGet(string $id): string
    {
        $response = $this->client->get(self::URI_PATH);

        $parsedResponse = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

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
     * @depends testGet
     */
    public function testPatchOk(string $id): string
    {
        $response = $this->client->patch(
            self::URI_PATH . '/' . $id,
            $this->requestOptions([
                'data' => [
                    'nombre' => self::NOMBRE_MODIFICADO,
                ],
            ])
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'data' => [
                    'id' => $id,
                    'nombre' => self::NOMBRE_MODIFICADO,
                ],
            ], JSON_THROW_ON_ERROR),
            (string) $response->getBody()
        );

        $parsedResponseGet = json_decode(
            (string) $this->client
                ->get(self::URI_PATH)
                ->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
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

    public function testPatchInexistente(): void
    {
        $response = $this->client->patch(
            self::URI_PATH . '/asd',
            $this->requestOptions([
                'data' => [
                    'nombre' => self::NOMBRE_ORIGINAL,
                ],
            ])
        );

        $parsedResponse = json_decode(
            (string) $response->getBody(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertSame(500, $response->getStatusCode());
        $this->assertIsString($parsedResponse->data->message);
    }

    /**
     * @depends testPatchOk
     */
    public function testPatchRepetido(string $id): array
    {
        $r = $this->client->post(
            self::URI_PATH,
            $this->requestOptions([
                'data' => [
                    'nombre' => self::NOMBRE_ORIGINAL,
                ],
            ])
        );

        $nuevoId = json_decode(
            (string) $r->getBody(),
            false,
            512,
            JSON_THROW_ON_ERROR
        )->data->id;

        $response = $this->client->patch(
            self::URI_PATH . '/' . $id,
            $this->requestOptions([
                'data' => [
                    'nombre' => self::NOMBRE_ORIGINAL,
                ],
            ])
        );

        $parsedResponse = json_decode(
            (string) $response->getBody(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertSame(500, $response->getStatusCode());
        $this->assertIsString($parsedResponse->data->message);

        return [$id, $nuevoId];
    }

    /**
     * @depends testPatchRepetido
     */
    public function testDeleteOk(array $ids): void
    {
        foreach ($ids as $id) {
            $response = $this->client->delete(self::URI_PATH . '/' . $id);

            $parsedResponse = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);

            $this->assertSame(200, $response->getStatusCode());
            $this->assertSame(null, $parsedResponse->data);

            $parsedResponseGet = json_decode(
                (string) $this->client
                    ->get(self::URI_PATH)
                    ->getBody(),
                true,
                512,
                JSON_THROW_ON_ERROR
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
    }

    public function testDeleteInexistente(): void
    {
        $response = $this->client->delete(self::URI_PATH . '/asd');

        $parsedResponse = json_decode(
            (string) $response->getBody(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertSame(500, $response->getStatusCode());
        $this->assertIsString($parsedResponse->data->message);
    }

    /**
     * @return (string|string[])[]
     *
     * @psalm-return array{headers: array{Content-Type: 'application/json'}, body?: string}
     */
    private function requestOptions(?array $body): array
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if ($body !== null) {
            $options['body'] = json_encode($body, JSON_THROW_ON_ERROR);
        }
        return $options;
    }
}
