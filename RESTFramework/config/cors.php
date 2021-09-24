<?php

declare(strict_types=1);

use Mezzio\Cors\Configuration\ConfigurationInterface;

return [
    ConfigurationInterface::CONFIGURATION_IDENTIFIER => [
        // Allow any origin
        'allowed_origins' => [ConfigurationInterface::ANY_ORIGIN],
        // No custom headers allowed
        'allowed_headers' => ['Content-type'],
        // 10 minutes
        'allowed_max_age' => '600',
        // Allow cookies
        'credentials_allowed' => true,
        // 'exposed_headers' => ['X-Custom-Header'], // Tell client that the API will always return this header
    ],
];
