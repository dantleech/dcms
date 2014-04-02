<?php

namespace spec\Dcms\Bundle\CoreBundle\Router;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EndpointRouterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\Router\EndpointRouter');
    }
}
