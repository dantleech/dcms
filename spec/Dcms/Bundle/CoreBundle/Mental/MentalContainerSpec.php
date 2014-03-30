<?php

namespace spec\Dcms\Bundle\CoreBundle\Mental;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dcms\Bundle\CoreBundle\Mental\Mental;

class DefaultMentalContainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\Mental\DefaultMentalContainer');
    }

    function it_can_have_mentals_registered_on_it(Mental $mental)
    {
        $mental->getName()->willReturn('mental1');
        $this->registerMental($mental);
        $this->getMental('mental1')->shouldReturn($mental);
    }
}
