<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion;

final class ConfigDiscover
{
    /**
     * @var string
     */
    private const ROOT_PATH = __DIR__ . '/../../../..';

    /**
     * @return mixed[]
     */
    public function find(string $fileName): array
    {
        foreach (new \DirectoryIterator(self::ROOT_PATH . '/app') as $directory) {
            $configs = [];
            if (! $directory->isDir()) {
                continue;
            }
            if ($directory->isDot()) {
                continue;
            }
            $presentacion = new \DirectoryIterator($directory->getPathname() . '/Presentacion');
            foreach ($presentacion as $directory) {
                if (! $directory->isDir()) {
                    continue;
                }
                if ($directory->isDot()) {
                    continue;
                }
                $configPath = $directory->getPathname() . sprintf('/%s.php', $fileName);
                if (file_exists($configPath)) {
                    $configs[] = require $configPath;
                }
            }
        }
        return $configs;
    }
}
