<?php

namespace Dcms\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Mental pass
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class MentalPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('dcms.mental_container')) {
            return;
        }

        $mentalContainerDefinition = $container->getDefinition('dcms.mental_container');

        $ids = $container->findTaggedServiceIds('dcms.mental');

        foreach (array_keys($ids) as $id) {
            $mentalContainerDefinition->addMethodCall('registerMental', array(
                new Reference($id)
            ));
        }
    }
}
