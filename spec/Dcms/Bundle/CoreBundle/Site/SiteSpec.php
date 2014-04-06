<?php

namespace spec\Dcms\Bundle\CoreBundle\Site;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Dcms\Bundle\CoreBundle\Site\Site;
use PHPCR\NodeInterface;

class SiteSpec extends ObjectBehavior
{
    function let(
        NodeInterface $siteNode
    )
    {
        $this->beConstructedWith($siteNode);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\Site\Site');
    }

    function it_should_return_the_parent_path(
        NodeInterface $siteNode
    )
    {
        $siteNode->getPath()->willReturn('/dcms/sites/dantleech.com/sites/foobar/sites/barfoo');
        $this->getSiteParentPath()->shouldReturn('/dcms/sites/dantleech.com/sites/foobar');

        $siteNode->getPath()->willReturn('/dcms/sites/dantleech.com/sites/foobar');
        $this->getSiteParentPath()->shouldReturn('/dcms/sites/dantleech.com');

        $siteNode->getPath()->willReturn('/dcms/sites/dantleech.com');
        $this->getSiteParentPath()->shouldReturn(null);
    }
}
