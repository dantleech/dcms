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
        $host      = $request->getHost();
        $pathInfo  = $request->getPathInfo();
        $hostsPath = $this->config->getHostsPath();

        $targetPath  = $hostsPath . '/' . $host;
        $defaultPath = $hostsPath . '/' . $this->config->getDefaultHost();

        $phpcrSession = $this->managerRegistry->getConnection();

        $try = array(
            $targetPath,
            $defaultPath,
        );

        foreach ($try as $path) {
            try {
                $siteNode = $phpcrSession->getNode($path);
                break;
            } catch (PathNotFoundException $e) {
            }
        }

        if (!$siteNode) {
            throw new SiteNotFoundException(sprintf(
                'Could not find site for host "%s" and default host "%s" was not found'
            ), $targetPath, $defaultPath);
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
        $routeNode   = $phpcrSession->getNode($routeFolderPath . $pathInfo);

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
}
