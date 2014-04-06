<?php

namespace Dcms\Bundle\CoreBundle\Router;

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use PHPCR\PathNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;

use Dcms\Bundle\CoreBundle\Config\DcmsConfig;
use Dcms\Bundle\CoreBundle\Mental\MentalContainer;
use Dcms\Bundle\CoreBundle\Website\Exception\WebsiteNotFoundException;
use Dcms\Bundle\CoreBundle\Site\SiteContext;
use Dcms\Bundle\CoreBundle\Site\Site;
use Dcms\Bundle\CoreBundle\Router\Exception\SiteNotFoundException;
use Psr\Log\LoggerInterface;
use Dcms\Bundle\CoreBundle\NodeFinder\NodeFinder;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class EndpointMatcher implements RequestMatcherInterface
{
    protected $managerRegistry;
    protected $config;
    protected $mentalContainer;
    protected $siteContext;
    protected $logger;
    protected $nodeFinder;

    public function __construct(
        ManagerRegistry $managerRegistry, 
        DcmsConfig $config, 
        MentalContainer $mentalContainer,
        SiteContext $siteContext,
        NodeFinder $nodeFinder,
        LoggerInterface $logger
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->config          = $config;
        $this->siteContext     = $siteContext;
        $this->nodeFinder    = $nodeFinder;
        $this->mentalContainer = $mentalContainer;
        $this->logger          = $logger;
    }

    public function matchRequest(Request $request)
    {
        $host         = $request->getHost();
        $fallbackSitePath = $this->joinPath([
            $this->config->getSitesPath(),
            $this->config->getFallbackSite(),
        ]);

        $pathInfo     = $request->getPathInfo();
        $hostsPath    = $this->config->getHostsPath();
        $targetPath  = $this->joinPath([$hostsPath, $host]);

        $phpcrSession = $this->managerRegistry->getConnection();

        $siteNode = null;
        $hostNode = null;
        $siteNodePath = null;

        try {
            $hostNode = $phpcrSession->getNode($targetPath);
            $this->logger->info('Found host "' . $targetPath . '"');

            try {
                $siteNodePath = $hostNode->getProperty('site')->getValue();
            } catch (PathNotFoundException $e) {
                $this->logger->info('  but site property is empty, thats not going to work...');
                continue;
            }
        } catch (PathNotFoundException $e) {
            $this->logger->info('Could not find host "' . $targetPath . '"');
        }


        $sitePaths = array(
            $siteNodePath,
            $fallbackSitePath,
        );

        foreach ($sitePaths as $siteNodePath) {
            if (!$siteNodePath) {
                continue;
            }

            try {
                $siteNode = $phpcrSession->getNode($siteNodePath);
                $this->logger->info('Found site "' . $siteNodePath . '"');
                break;
            } catch (PathNotFoundException $e) {
                $this->logger->info('Could not find site "' . $siteNodePath . '"');
            }
        }

        if (!$siteNode) {
            $this->logger->info('Could not find anything. bye!');

            throw new SiteNotFoundException(sprintf(
                'Could not find a host for "%s" and default site also failed. See log for more details.'
            , $host));
        }

        $site = new Site($siteNode);
        $this->siteContext->setSite($site);

        $defaults = $this->matchPathInfo($pathInfo);

        return $defaults;
    }

    /**
     * Only do dumb 1-1 route matching for now
     *
     * @param string $pathInfo
     */
    protected function matchPathInfo($pathInfo)
    {
        $phpcrSession = $this->managerRegistry->getConnection();

        $routeFolderName = $this->config->getEndpointFolderName();
        $relativePath    = $this->joinPath([$routeFolderName, $pathInfo]);
        $routeNode       = $this->nodeFinder->findNode($relativePath);

        if (!$routeNode) {
            throw new RouteNotFoundException(
                'Could not find endpoint for "' . $pathInfo . '"'
            );
        }

        $deaults = array();

        $mentalNode         = $routeNode->getNode('mental');
        $mentalNodeType     = $mentalNode->getPrimaryNodeType();
        $mentalNodeTypeName = $mentalNodeType->getName();
        $mental             = $this->mentalContainer->getMental($mentalNodeTypeName);
        $defaults           = $mental->getEndpointDefaults($routeNode);

        return $defaults;
    }

    private function joinPath($els)
    {
        return $this->normalizePath(join('/', $els));
    }

    private function normalizePath($path)
    {
        $path = str_replace('//', '/', $path);
        if (substr($path, -1) == '/') {
            $path = substr($path, 0, -1);
        }

        return $path;
    }
}
