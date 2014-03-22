<?php

namespace Dcms\Bundle\CoreBundle\Router;

use Symfony\Component\HttpFoundation\Request;
use Dcms\Bundle\CoreBundle\Website\Exception\WebsiteNotFoundException;

class EndpointMatcher extends RequestMatcherInterface
{
    protected $session;
    protected $context;

    public function __construct(PhpcrSession $session, DcmsContext $session, MentalFactory $mentalFactory)
    {
        $this->session       = $session;
        $this->context       = $context;
        $this->mentalFactory = $mentalFactory;
    }

    public function matchRequest(Request $request)
    {
        $host              = $request->getHost();
        $pathInfo          = $request->getPathInfo();
        $siteFolderPath    = $this->context->config()->get('path/site-folder');
        $requestedSitePath = PathUtil::join($siteFolderPath, $host);
        $siteNode          = $session->getNode($requestedSitePath);

        if ($siteNode) {
            $this->context->setCurrentSiteNode($siteNode);
        } else {
            $defaultSiteHost = $this->context->config()->get('routing/default-host');
            $sitePath        = PathUtil::join($siteFolder, $defaultSiteHost);
            $siteNode        = $session->getNode($sitePath);
        }

        if (!$siteNode) {
            throw new WebsiteNotFoundException(sprintf(
                'Could not find website for host "%s" and default site "%s" not defined.',
                $requestedSitePath,
                $defaultSitePath
            ));
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
        $endpointFolder = $this->context->config()->get('path/endpoint-folder-name');
        $endpointNode   = $this->session->getNode($endpointPath . '/' . $pathInfo);

        if (!$endpoint) {
            throw new ResourceNotFoundException(sprintf(
                'Cannot find a endpoint "%s"',
                $endpointPath
            ));
        }

        $deaults = array();

        $mentalName = $endpoint->getProperty('mental');
        $mental     = $this->mentalFactory->get($mentalName);
        $defaults   = $mental->getDefaults($endpoint);

        return $defaults;
    }
}
