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

    function it_can_return_the_path_of_the_current_site_and_those_of_ites_ancestors(
        Site $site
    )
    {
        $this->setSite($site);

        $site->getPath()->willReturn('/sites/dantleech.com/sites/foobar');
        $this->getSiteCascadePaths()->shouldReturn(array(
            '/sites/dantleech.com/sites/foobar',
            '/sites/dantleech.com'
        ));

        $site->getPath()->willReturn('/sites/dantleech.com');
        $this->getSiteCascadePaths()->shouldReturn(array(
            '/sites/dantleech.com'
        ));

        $site->getPath()->willReturn('/sites/dantleech.com/sites/foobar.com/sites/diebar.bom');
        $this->getSiteCascadePaths()->shouldReturn(array(
            '/sites/dantleech.com/sites/foobar.com/sites/diebar.bom',
            '/sites/dantleech.com/sites/foobar.com',
            '/sites/dantleech.com',
        ));
    }
}
