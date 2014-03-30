<?php

namespace spec\Dcms\Bundle\CoreBundle\Site;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dcms\Bundle\CoreBundle\Site\Site;

class SiteContextSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\Site\SiteContext');
    }

    function it_has_a_method_which_returns_the_current_site(Site $site)
    {
        $this->setSite($site);
        $this->getSite()->shouldReturn($site);
    }
}
