<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\LevelSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/Bundle/*/src'
    ]);

    $containerConfigurator->import(LevelSetList::UP_TO_PHP_80);
    $services = $containerConfigurator->services();

    // risky rule
    $services->remove(\Rector\Php80\Rector\FunctionLike\UnionTypesRector::class);

    // uncomprehensive spaceship operator
    $services->remove(\Rector\Php70\Rector\If_\IfToSpaceshipRector::class);

    // avoid auto promotion
    $services->remove(\Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector::class);

    $containerConfigurator->import(\Rector\Symfony\Set\SymfonySetList::SYMFONY_60);
    $containerConfigurator->import(\Rector\Symfony\Set\SymfonySetList::SYMFONY_CODE_QUALITY);
    $containerConfigurator->import(\Rector\Symfony\Set\SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION);
};
