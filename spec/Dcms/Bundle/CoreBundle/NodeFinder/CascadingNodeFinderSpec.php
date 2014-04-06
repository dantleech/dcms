<?php

namespace spec\Dcms\Bundle\CoreBundle\NodeFinder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\Common\Persistence\ManagerRegistry;
use Dcms\Bundle\CoreBundle\Config\DcmsConfig;
use Dcms\Bundle\CoreBundle\Site\SiteContext;
use Dcms\Bundle\CoreBundle\Site\Site;
use PHPCR\SessionInterface;
use PHPCR\NodeInterface;

class CascadingNodeFinderSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $managerRegistry,
        SiteContext $siteContext,
        SessionInterface $session
    )
    {
        $this->beConstructedWith(
            $managerRegistry,
            $siteContext
        );

        $managerRegistry->getConnection()->willReturn($session);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\NodeFinder\CascadingNodeFinder');
    }

    function it_can_find_a_node_from_a_site_hierarchy(
        Site $site1,
        Site $site2,
        SiteContext $siteContext,
        SessionInterface $session,
        NodeInterface $node
    )
    {
        $siteContext->getSiteCascadePaths()->willReturn(array(
            '/sites/default/sites/site1',
        ));
        $site1->getPath()->willReturn('/sites/default/sites/site1');
        $session->getNode('/sites/default/sites/site1/relpath')->willThrow('PHPCR\PathNotFoundException');
        $session->getNode('/sites/site1/relpath')->willReturn($node);

        $this->findNode('relpath')->shouldReturn($node);
    }
}
