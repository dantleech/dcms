<?php

namespace spec\Dcms\Bundle\CoreBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DcmsCoreBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\DcmsCoreBundle');
    }

    function it_should_add_the_mental_compiler_pass(ContainerBuilder $container)
    {
        $container->addCompilerPass(Argument::type('Dcms\Bundle\CoreBundle\DependencyInjection\Compiler\MentalPass'))->shouldBeCalled();
        $this->build($container);
    }
}
