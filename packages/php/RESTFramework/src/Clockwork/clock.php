<?php

declare(strict_types=1);

use Consultorios\RESTFramework\Clockwork\Clockwork;

if (! \function_exists('clock')) {
    // Log a message to Clockwork, returns Clockwork instance when called with no arguments, first argument otherwise
    function clock(mixed ...$arguments): mixed
    {
        $clockwork = Clockwork::instance();
        if (empty($arguments)) {
            return $clockwork;
        }

        foreach ($arguments as $argument) {
            $clockwork->debug($argument);
        }

        return \reset($arguments);
    }
}
