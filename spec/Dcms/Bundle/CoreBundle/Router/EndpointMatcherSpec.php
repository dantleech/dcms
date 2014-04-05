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

class EndpointMatcherSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $managerRegistry,
        MentalContainer $mentalContainer,
        DcmsConfig $dcmsConfig,
        Mental $mental,
        SessionInterface $phpcrSession,
        SiteContext $siteContext,
        NodeInterface $siteNode,
        NodeInterface $routeNode,
        Site $site,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($managerRegistry, $dcmsConfig, $mentalContainer, $siteContext, $logger);

        $managerRegistry->getConnection()->willReturn($phpcrSession);

        $phpcrSession->getNode('/dcms/hosts/dantleech.com')->willReturn($siteNode);
        $phpcrSession->getNode('/dcms/sites/dantleech.com/routes/test')->willReturn($routeNode);

        $dcmsConfig->getHostsPath()->willReturn('/dcms/hosts');
        $dcmsConfig->getDefaultHost()->willReturn('default.dom');
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
        NodeInterface $routeNode
    ) {
        $routeNode->getProperty('dcms:mental')->willReturn('test_mental');
        $mentalContainer->getMental('test_mental')->willReturn($mental);
        $mental->getEndpointDefaults($routeNode)->willReturn(array(
            '_controller' => 'MentalBundle:mental:action'
        ));

        $siteContext->setSite(Argument::type('Dcms\Bundle\CoreBundle\Site\Site'))->shouldBeCalled();
        $request->getHost()->willReturn('dantleech.com');

        $request->getPathInfo()->willReturn('/test');
        $this->matchRequest($request)->shouldReturn(array(
            '_controller' => 'MentalBundle:mental:action'
        ));
    }
}
