<?php

declare(strict_types=1);

if (! \function_exists('clock')) {
    // Log a message to Clockwork, returns Clockwork instance when called with no arguments, first argument otherwise
    function clock(mixed ...$arguments): mixed
    {
        $coreContainer = new \Consultorio\Core\CoreContainer(new class() implements \Psr\Container\ContainerInterface {
            public function has(string $id)
            {
                return false;
            }

            public function get(string $id)
            {
                return null;
            }
        });

        if (empty($arguments)) {
            return $coreContainer->clockwork();
        }

        foreach ($arguments as $argument) {
            $coreContainer->clockwork()->debug($argument);
        }

        return \reset($arguments);
    }
}
