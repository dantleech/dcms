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

class EndpointMatcher implements RequestMatcherInterface
{
    protected $managerRegistry;
    protected $config;
    protected $mentalContainer;
    protected $siteContext;

    public function __construct(
        ManagerRegistry $managerRegistry, 
        DcmsConfig $config, 
        MentalContainer $mentalContainer,
        SiteContext $siteContext
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->config          = $config;
        $this->siteContext     = $siteContext;
        $this->mentalContainer = $mentalContainer;
    }

    public function matchRequest(Request $request)
    {
        $host        = $request->getHost();
        $defaultHost = $this->config->getDefaultHost();
        $pathInfo    = $request->getPathInfo();
        $hostsPath   = $this->config->getHostsPath();

        $targetPath  = $this->joinPath([$hostsPath, $host]);
        $defaultPath = $this->joinPath([$hostsPath, $defaultHost]);

        $phpcrSession = $this->managerRegistry->getConnection();

        $try = array(
            $targetPath,
            $defaultPath,
        );

        $siteNode = null;
        $hostNode = null;
        foreach ($try as $path) {
            try {
                $hostNode = $phpcrSession->getNode($path);
                $siteNodePath = $hostNode->getProperty('site')->getValue();
                $siteNode = $phpcrSession->getNode($siteNodePath);
                break;
            } catch (PathNotFoundException $e) {
            }
        }

        if (!$siteNode) {
            throw new SiteNotFoundException(sprintf(
                'Could not find site for host "%s" and default host "%s" was not found'
            , $host, $defaultHost));
        }

        if ($siteNode) {
            $site = new Site($siteNode);
            $this->siteContext->setSite($site);
        }

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
        $routeFolderPath = $this->siteContext->getAbsPathFor($routeFolderName);
        $routeNode       = $phpcrSession->getNode($this->joinPath([$routeFolderPath, $pathInfo]));

        if (!$routeNode) {
            throw new ResourceNotFoundException(sprintf(
                'Cannot find a route "%s"',
                $routePath
            ));
        }

        $deaults = array();

        $mentalName = $routeNode->getProperty('dcms:mental');
        $mental     = $this->mentalContainer->getMental($mentalName);
        $defaults   = $mental->getEndpointDefaults($routeNode);

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
