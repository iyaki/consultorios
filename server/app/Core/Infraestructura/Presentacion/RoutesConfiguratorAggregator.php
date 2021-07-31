<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion;

use Consultorio\Core\CoreContainer;
use Consultorio\Core\Presentacion\AbstractRoutesConfigurator;
use Consultorio\Core\Presentacion\RoutesConfiguratorInterface;
use Laminas\ServiceManager\ServiceManager;

final class RoutesConfiguratorAggregator extends AbstractRoutesConfigurator
{
    /**
     * @var string
     */
    private const ROOT_PATH = __DIR__ . '/../../../../app/';

    private CoreContainer $coreContainer;

    public function __construct(ServiceManager $container, CoreContainer $coreContainer)
    {
        parent::__construct($container, $coreContainer);
        $this->coreContainer = $coreContainer;
    }

    public function configure(): void
    {
        foreach ($this->aggregatedRoutesConfigurators() as $routeConfigurator) {
            if (! is_subclass_of($routeConfigurator, RoutesConfiguratorInterface::class)) {
                continue;
            }
            ($routeConfigurator::create($this->container(), $this->coreContainer))->configure();
        }
    }

    /**
     * @return string[]
     */
    private function aggregatedRoutesConfigurators(): array
    {
        return array_diff(
            array_map(
                fn (string $f) => $this->getFQCNFrom($f),
                $this->routesConfiguratorsFiles()
            ),
            [
                self::class,
                \Consultorio\Core\Presentacion\AbstractRoutesConfigurator::class,
                'Consultorio\Core\Presentacion\\',
            ]
        );
    }

    private function getFQCNFrom(string $fileName): string
    {
        $fp = fopen($fileName, 'r');
        $class = '';
        $namespace = '';

        while ($class === '' && ! feof($fp)) {
            $buffer = fread($fp, 512);
            $tokens = \PhpToken::tokenize($buffer);

            if (! str_contains($buffer, '{')) {
                continue;
            }

            $tokensCount = count($tokens);
            for ($i = 0; $i < $tokensCount; ++$i) {
                if ($tokens[$i]->id === T_NAMESPACE) {
                    for ($j = $i + 1; $j < $tokensCount; ++$j) {
                        if ($tokens[$j]->id === T_NAME_QUALIFIED) {
                            $namespace .= '\\' . $tokens[$j]->text;
                        } elseif ($tokens[$j]->text === '{' || $tokens[$j]->text === ';') {
                            break;
                        }
                    }
                }

                if ($tokens[$i]->id === T_CLASS) {
                    for ($j = $i + 1; $j < $tokensCount; ++$j) {
                        if ($tokens[$j]->text === '{') {
                            $class = $tokens[$i + 2]->text;
                        }
                    }
                }
            }
        }
        return substr($namespace . '\\' . $class, 1);
    }

    /**
     * @return string[]
     */
    private function routesConfiguratorsFiles(): array
    {
        // TODO: Implement cache strategy for production

        $configs = [];

        $rdi = new \RecursiveDirectoryIterator(self::ROOT_PATH, \RecursiveDirectoryIterator::KEY_AS_PATHNAME);
        foreach (new \RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::SELF_FIRST) as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $realPath = $file->getRealPath();
            $pathinfo = pathinfo($realPath);
            if (($pathinfo['extension'] ?? null) !== 'php') {
                continue;
            }
            if (preg_match('#.*RoutesConfigurator.*#', $pathinfo['filename']) !== 1) {
                continue;
            }

            $configs[] = $realPath;
        }

        return $configs;
    }
}
