<?php

namespace Dcms\Bundle\CoreBundle\Twig;

class DcmsLoader implements \Twig_LoaderInterface
{
    protected $managerRegistry;
    protected $config;
    protected $siteContext;
    protected $logger;

    public function __construct(
        ManagerRegistry $managerRegistry, 
        DcmsConfig $config, 
        SiteContext $siteContext,
        LoggerInterface $logger
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->resourceResolver= $resourceResolver;
        $this->mentalContainer = $mentalContainer;
        $this->logger          = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function getSource($name)
    {
        $siteNode = $this->siteContext
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheKey($name)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function isFresh($name, $time)
    {
    }
}
