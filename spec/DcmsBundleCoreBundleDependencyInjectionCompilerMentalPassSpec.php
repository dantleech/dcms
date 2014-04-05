<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DcmsBundleCoreBundleDependencyInjectionCompilerMentalPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('DcmsBundleCoreBundleDependencyInjectionCompilerMentalPass');
    }
}
