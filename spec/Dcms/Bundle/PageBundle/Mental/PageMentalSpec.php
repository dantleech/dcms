<?php

namespace spec\Dcms\Bundle\PageBundle\Mental;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageMentalSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\PageBundle\Mental\PageMental');
    }
}
