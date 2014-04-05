<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DcmsBundleCoreBundleConfigDcmsConfigSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('DcmsBundleCoreBundleConfigDcmsConfig');
    }
}
