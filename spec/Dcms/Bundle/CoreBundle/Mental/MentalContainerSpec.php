<?php

namespace spec\Dcms\Bundle\CoreBundle\Mental;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dcms\Bundle\CoreBundle\Mental\Mental;

class MentalContainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\Mental\MentalContainer');
    }

    function it_can_have_mentals_registered_on_it(Mental $mental)
    {
        $mental->getTargetNodeTypeName()->willReturn('dcms:mental:one');
        $this->registerMental($mental);
        $this->getMental('dcms:mental:one')->shouldReturn($mental);
    }
}
