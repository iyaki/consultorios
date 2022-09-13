<?php

declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\Extend;
use Arkitect\Expression\ForClasses\HaveAttribute;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\Implement;
use Arkitect\Expression\ForClasses\IsFinal;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\RuleBuilders\Architecture\Architecture;
use Arkitect\Rules\Rule;
use League\Fractal\TransformerAbstract;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;
use Psr\Http\Server\RequestHandlerInterface;

return static function (Config $config): void {
    $classSet = ClassSet::fromDir(__DIR__.'/app');

    $layeredArchitectureRules = Architecture::withComponents()
        ->component('Handlers')->definedBy('Consultorios\WebApp\**\*Handler')
        ->component('Transformers')->definedBy('Consultorios\WebApp\**\*Transformer')
        ->component('Tests')->definedBy('Consultorios\WebApp\**\*Test')
        ->component('RESTFrameworkResponseFactory')->definedBy('Consultorios\RESTFramework\Consultorios\RESTFramework\ResponseFactory')
        ->component('FractalTransformerAbstract')->definedBy('League\Fractal\TransformerAbstract')
        ->component('PSR7')->definedBy('Psr\Http\Message\*')
        ->component('PSR15Handler')->definedBy('Psr\Http\Server\RequestHandlerInterface')
        ->component('CoreAgendasDomain')->definedBy('Consultorios\Agendas\Domain\*')
        ->component('CoreAgendasInfrastructure')->definedBy('Consultorios\Agendas\Infrastructure\*')
        ->component('CoreAgendasUseCases')->definedBy('Consultorios\Agendas\UseCases\*')
        ->component('OpenApiAttributes')->definedBy('OpenApi\Attributes\*')

        ->where('Handlers')->mayDependOnComponents(
            'RESTFrameworkResponseFactory',
            'PSR7',
            'PSR15Handler',
            'CoreAgendasDomain',
            'CoreAgendasUseCases',
            'OpenApiAttributes'
        )
        ->where('Transformers')->mayDependOnComponents(
            'FractalTransformerAbstract',
            'CoreAgendasDomain',
            'OpenApiAttributes'
        )

        ->rules();

        $moreRules = [
            Rule::allClasses()
                ->that(new Implement(RequestHandlerInterface::class))
                ->should(new HaveNameMatching('*Handler'))
                ->because('Uniformidad en los nombre de los handlers'),
            Rule::allClasses()
                ->that(new ResideInOneOfTheseNamespaces('Consultorios\WebApp\**\*Handler'))
                ->should(new Implement(RequestHandlerInterface::class))
                ->because('Los handlers deben ser PSR15 compliant'),
            Rule::allClasses()
                ->that(new Implement(RequestHandlerInterface::class))
                ->should(new IsFinal())
                ->because('Los handlers deben ser clases finales'),
            // Rule::allClasses()
            //     ->that(new Implement(RequestHandlerInterface::class))
            //     ->should(new HaveAttribute(Response::class))
            //     ->because('Los handlers deben documentarse'),
                Rule::allClasses()
                ->that(new Extend(TransformerAbstract::class))
                ->should(new HaveNameMatching('*Transformer'))
                ->because('Uniformidad en los nombre de los transformers'),
            Rule::allClasses()
                ->that(new ResideInOneOfTheseNamespaces('Consultorios\WebApp\**\*Transformer'))
                ->should(new Extend(TransformerAbstract::class))
                ->because('Los transformers deben ser League/Fractal compliant'),
            Rule::allClasses()
                ->that(new Extend(TransformerAbstract::class))
                ->should(new IsFinal())
                ->because('Los transformers deben ser clases finales'),
            Rule::allClasses()
                ->that(new Extend(TransformerAbstract::class))
                ->should(new HaveAttribute(Schema::class))
                ->because('Los transformers deben documentarse'),
        ];

    $config->add(
        $classSet,
        ...[
            ...$layeredArchitectureRules,
            ...$moreRules,
        ]
    );
};
