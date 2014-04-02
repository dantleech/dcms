<?php

namespace spec\Dcms\CoreBundle\Router;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EndpointRouterSpec extends ObjectBehavior
{
    function let()
    {
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\CoreBundle\Router\EndpointRouter');
    }
}
