<?php

namespace Dcms\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class CoreBundleExtension extends Extension
{
    public function build(ContainerBuilder $builder, $config)
    {
        $loader = new XmlFileLoader();
        $loader->load('services.xml');
    }
}
