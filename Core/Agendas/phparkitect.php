<?php

declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\RuleBuilders\Architecture\Architecture;

return static function (Config $config): void {
    $classSet = ClassSet::fromDir(__DIR__.'/src');

    $layeredArchitectureRules = Architecture::withComponents()
        ->component('Domain')->definedBy('Consultorios\Agendas\Domain\*')
        ->component('Infrastructure')->definedBy('Consultorios\Agendas\UseCases\*')
        ->component('UseCases')->definedBy('Consultorios\Agendas\Domain\*')

        ->where('Domain')->shouldOnlyDependOnComponents('Domain')
        ->where('Infrastructure')->mayDependOnComponents('Domain')
        ->where('UseCases')->mayDependOnComponents('Domain')

        ->rules();

    $config->add($classSet, ...$layeredArchitectureRules);
};
