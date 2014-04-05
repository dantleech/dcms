<?php

namespace Dcms\Bundle\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Dcms\Bundle\CoreBundle\DependencyInjection\Compiler\MentalPass;

class DcmsCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new MentalPass());
    }
}
