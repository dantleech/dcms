<?php

namespace spec\Dcms\Bundle\CoreBundle\Router;

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use PHPCR\NodeInterface;
use PHPCR\SessionInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

use Dcms\Bundle\CoreBundle\Config\DcmsConfig;
use Dcms\Bundle\CoreBundle\Mental\MentalContainer;
use Dcms\Bundle\CoreBundle\Site\SiteContext;
use Dcms\Bundle\CoreBundle\Site\Site;
use Dcms\Bundle\CoreBundle\Mental\Mental;
use Psr\Log\LoggerInterface;
use Dcms\Bundle\CoreBundle\NodeFinder\NodeFinder;
use PHPCR\PropertyInterface;
use PHPCR\NodeType\NodeTypeInterface;

class EndpointMatcherSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $managerRegistry,
        MentalContainer $mentalContainer,
        DcmsConfig $dcmsConfig,
        Mental $mental,
        SessionInterface $phpcrSession,
        SiteContext $siteContext,
        NodeInterface $hostNode,
        NodeInterface $routeNode,
        NodeInterface $siteNode,
        Site $site,
        NodeFinder $nodeFinder,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($managerRegistry, $dcmsConfig, $mentalContainer, $siteContext, $nodeFinder, $logger);

        $managerRegistry->getConnection()->willReturn($phpcrSession);

        $phpcrSession->getNode('/dcms/hosts/dantleech.com')->willReturn($hostNode);
        $phpcrSession->getNode('/dcms/sites/dantleech.com/routes/test')->willReturn($routeNode);
        $phpcrSession->getNode('/dcms/sites/dantleech.com')->willReturn($siteNode);

        $dcmsConfig->getHostsPath()->willReturn('/dcms/hosts');
        $dcmsConfig->getSitesPath()->willReturn('/dcms/sites');
        $dcmsConfig->getFallbackSite()->willReturn('default');
        $dcmsConfig->getEndpointFolderName()->willReturn('routes');
        $siteContext->getAbsPathFor('routes')->willReturn('/dcms/sites/dantleech.com/routes');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Dcms\Bundle\CoreBundle\Router\EndpointMatcher');
    }

    function it_should_match_the_incoming_hostname(
        Request $request,
        SiteContext $siteContext,
        MentalContainer $mentalContainer,
        Mental $mental,
        NodeInterface $routeNode,
        NodeInterface $hostNode,
        PropertyInterface $siteProperty,
        NodeFinder $nodeFinder,
        NodeInterface $mentalNode,
        NodeTypeInterface $mentalNodeType
    ) {
        $siteProperty->getValue()->willReturn('/dcms/sites/dantleech.com');
        $hostNode->getProperty('site')->willReturn($siteProperty);
        $routeNode->getNode('mental')->willReturn($mentalNode);
        $mentalNode->getPrimaryNodeType()->willReturn($mentalNodeType);
        $mentalNodeType->getName()->willReturn('test:mental');
        $mentalContainer->getMental('test:mental')->willReturn($mental);
        $mental->getEndpointDefaults($routeNode)->willReturn(array(
            '_controller' => 'MentalBundle:mental:action'
        ));
        $nodeFinder->findNode('routes/test')->willReturn($routeNode);

        $siteContext->setSite(Argument::type('Dcms\Bundle\CoreBundle\Site\Site'))->shouldBeCalled();
        $request->getHost()->willReturn('dantleech.com');

        $request->getPathInfo()->willReturn('/test');
        $this->matchRequest($request)->shouldReturn(array(
            '_controller' => 'MentalBundle:mental:action'
        ));
    }
}
