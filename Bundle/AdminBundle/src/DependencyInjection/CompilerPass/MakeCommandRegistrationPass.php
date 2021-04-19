<?php

namespace Umbrella\AdminBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Umbrella\AdminBundle\Command\MakerCommand;
use Umbrella\AdminBundle\Maker\Generator\Generator;
use Umbrella\AdminBundle\Maker\MakerInterface;
use Umbrella\AdminBundle\Maker\Utils\MakerUtils;

/**
 * Class MakeCommandRegistrationPass
 */
class MakeCommandRegistrationPass implements CompilerPassInterface
{
    const MAKER_TAG = 'umbrella.maker';

    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds(self::MAKER_TAG) as $id => $tags) {
            $def = $container->getDefinition($id);
            $class = $container->getParameterBag()->resolveValue($def->getClass());
            if (!is_subclass_of($class, MakerInterface::class)) {
                throw new InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, MakerInterface::class));
            }

            $container->register(
                sprintf('maker.auto_command.%s', MakerUtils::asSnakeCase($class::getCommandName())),
                MakerCommand::class
            )->setArguments([
                new Reference($id),
                new Reference(Generator::class),
            ])->addTag('console.command', ['command' => $class::getCommandName()]);
        }
    }
}
