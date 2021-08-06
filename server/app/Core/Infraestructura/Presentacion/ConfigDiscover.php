<?php

declare(strict_types=1);

namespace Consultorio\Core\Infraestructura\Presentacion;

final class ConfigDiscover
{
    /**
     * @var string
     */
    private const ROOT_PATH = __DIR__ . '/../../../';

    /**
     * @return string[]
     */
    public function find(string $fileName): array
    {
        // TODO: Implement cache strategy for production

        $configs = [];

        $rdi = new \RecursiveDirectoryIterator(realpath(self::ROOT_PATH), \RecursiveDirectoryIterator::KEY_AS_PATHNAME);
        foreach (new \RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::SELF_FIRST) as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $realPath = $file->getRealPath();
            $pathinfo = pathinfo($realPath);
            if (($pathinfo['extension'] ?? null) !== 'php') {
                continue;
            }
            if ($fileName === $pathinfo['filename']) {
                $configs[] = $realPath;
            }
        }

        return $configs;
    }
}
