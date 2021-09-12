<?php

declare(strict_types=1);

namespace Consultorios\Presentations\Common\REST\Framework\Clockwork;

use Clockwork\DataSource\PsrMessageDataSource;
use Clockwork\Helpers\ServerTiming;
use Consultorio\Core\CoreContainer;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ClockworkMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private const AUTH_URI = '#/__clockwork/auth#';

    /**
     * @var string
     */
    private const CLOCKWORK_DATA_URI = '#/__clockwork(?:/(?<id>[0-9-]+))?(?:/(?<direction>(?:previous|next)))?(?:/(?<count>\d+))?#';

    private \Clockwork\Clockwork $clockwork;

    private float $startTime;

    public function __construct()
    {
        $this->clockwork = Clockwork::instance();
        $this->startTime = microtime(true);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (preg_match(self::AUTH_URI, $request->getUri()->getPath(), $matches)) {
            return $this->authenticate($request);
        }
        if (preg_match(self::CLOCKWORK_DATA_URI, $request->getUri()->getPath(), $matches)) {
            $matches = array_merge([
                'id' => null,
                'direction' => null,
                'count' => null,
            ], $matches);
            return $this->retrieveRequest($request, $matches['id'], $matches['direction'], $matches['count']);
        }

        include __DIR__ . '/clock.php';

        $response = $handler->handle($request);

        return $this->logRequest($request, $response);
    }

    private function authenticate(ServerRequestInterface $request): \Laminas\Diactoros\Response\JsonResponse
    {
        $token = $this->clockwork->authenticator()->attempt($request->getParsedBody());

        return $this->jsonResponse([
            'token' => $token,
        ], $token ? 200 : 403);
    }

    private function retrieveRequest(ServerRequestInterface $request, ?string $id, ?string $direction, ?string $count): \Laminas\Diactoros\Response\JsonResponse
    {
        $authenticator = $this->clockwork->authenticator();
        $storage = $this->clockwork->storage();

        $authenticated = $authenticator->check(current($request->getHeader('X-Clockwork-Auth')));

        if ($authenticated !== true) {
            return $this->jsonResponse([
                'message' => $authenticated,
                'requires' => $authenticator->requires(),
            ], 403);
        }

        if ($direction === 'previous') {
            $data = $storage->previous($id, $count);
        } elseif ($direction === 'next') {
            $data = $storage->next($id, $count);
        } elseif ($id === 'latest') {
            $data = $storage->latest();
        } else {
            $data = $storage->find($id);
        }

        return $this->jsonResponse($data);
    }

    private function logRequest(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->clockwork->timeline()->finalize($this->startTime);
        $this->clockwork->addDataSource(new PsrMessageDataSource($request, $response));

        $this->clockwork->resolveRequest();
        $this->clockwork->storeRequest();

        $clockworkRequest = $this->clockwork->request();

        $response = $response
            ->withHeader('X-Clockwork-Id', $clockworkRequest->id)
            ->withHeader('X-Clockwork-Version', \Clockwork\Clockwork::VERSION);

        return $response->withHeader('Server-Timing', ServerTiming::fromRequest($clockworkRequest)->value());
    }

    private function jsonResponse(mixed $data, int $status = 200): \Laminas\Diactoros\Response\JsonResponse
    {
        return new JsonResponse($data, $status);
    }
}
