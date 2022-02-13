<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/Bundle/*/src'
    ]);

    // Define what rule sets will be applied
    // $containerConfigurator->import(LevelSetList::UP_TO_PHP_80);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();
    $services->set(\Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector::class);
};
