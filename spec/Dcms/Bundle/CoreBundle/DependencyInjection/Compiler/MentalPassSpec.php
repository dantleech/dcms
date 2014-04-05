<?php

namespace spec\Dcms\Bundle\CoreBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MentalPassSpec extends ObjectBehavior
{
    function let(ContainerBuilder $container)
    {
        $this->beConstructedWith($container);
    }

    function it_should_add_tagged_mentals_to_the_mental_container(
        ContainerBuilder $container,
        Definition $mentalContainerDef
    )
    {
        $container->hasDefinition('dcms.mental_container')->willReturn(true);
        $container->getDefinition('dcms.mental_container')->willReturn($mentalContainerDef);

        $container->findTaggedServiceIds('dcms.mental')->willReturn(
            'mental_1', 'mental_2'
        );

        $mentalContainerDef->addMethodCall('addMental', Argument::type('Symfony\Component\DependencyInjection\Reference'));
    }
}
