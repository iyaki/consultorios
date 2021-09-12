<?php

declare(strict_types=1);

return (function () {
    $devMode = (bool) getenv('DEV_MODE');

    return [
        'debug' => $devMode,
        'dev_mode' => $devMode,
        'router' => [
            'fastroute' => [
                'cache_enabled' => false,
                'cache_file' => 'data/cache/fastroute.php.cache',
            ],
        ],
    ];
})();
