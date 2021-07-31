<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Presentacion\WebApp;

use Consultorio\Agendas\AgendasContainer;
use Consultorio\Agendas\Infraestructura\Presentacion\WebApp\WebAppResponseFactoryAgendasFractal;
use Consultorio\Core\CoreContainer;
use Consultorio\Core\Presentacion\AbstractRoutesConfigurator;
use Laminas\ServiceManager\ServiceManager;
use Psr\Http\Message\ResponseFactoryInterface;

final class RoutesConfiguratorAgendasWebApp extends AbstractRoutesConfigurator
{
    /**
     * @var string
     */
    private const BASE_PATH = '/agendas/webapp/';

    private AgendasContainer $agendasContainer;

    public function __construct(ServiceManager $serviceManager, CoreContainer $coreContainer)
    {
        parent::__construct($serviceManager, $coreContainer);
        $this->agendasContainer = new AgendasContainer($coreContainer);
    }

    public function configure(): void
    {
        /** @var \Mezzio\Application $app */
        $app = $this->container()->get(\Mezzio\Application::class);

        $app->delete(self::BASE_PATH . 'especialidades/{id}', new EspecialidadesDeleteHandler(
            $this->responseFactory(),
            $this->agendasContainer->getCasosDeUsoEspecialidades()
        ));
        $app->get(self::BASE_PATH . 'especialidades', new EspecialidadesGetHandler(
            $this->responseFactory(),
            $this->agendasContainer->getCasosDeUsoEspecialidades()
        ));
        $app->patch(self::BASE_PATH . 'especialidades/{id}', new EspecialidadesPatchHandler(
            $this->responseFactory(),
            $this->agendasContainer->getCasosDeUsoEspecialidades()
        ));
        $app->post(self::BASE_PATH . 'especialidades', new EspecialidadesPostHandler(
            $this->responseFactory(),
            $this->agendasContainer->getCasosDeUsoEspecialidades()
        ));
    }

    private function responseFactory(): \Consultorio\Agendas\Infraestructura\Presentacion\WebApp\WebAppResponseFactoryAgendasFractal
    {
        return new WebAppResponseFactoryAgendasFractal(
            $this->container()->get(ResponseFactoryInterface::class)
        );
    }
}
