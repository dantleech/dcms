<?php

namespace spec\Dcms\Bundle\CoreBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DcmsCoreExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\DependencyInjection\DcmsCoreExtension');
    }
}
