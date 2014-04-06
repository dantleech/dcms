<?php

namespace Dcms\Bundle\CoreBundle\NodeFinder;

use Doctrine\Common\Persistence\ManagerRegistry;
use Dcms\Bundle\CoreBundle\Config\DcmsConfig;
use Dcms\Bundle\CoreBundle\Site\SiteContext;
use PHPCR\PathNotFoundException;

class CascadingNodeFinder implements NodeFinder
{
    protected $managerRegistry;
    protected $siteContext;

    public function __construct(
        ManagerRegistry $managerRegistry,
        SiteContext $siteContext
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->siteContext = $siteContext;
    }

    public function findNode($relativePath)
    {
        $session = $this->managerRegistry->getConnection();
        $basePaths = $this->siteContext->getSiteCascadePaths();

        foreach ($basePaths as $basePath) {
            $path = $basePath . '/' . $relativePath;
            try {
                return $session->getNode($path);
            } catch (PathNotFoundException $e) {
            }
        }

        return null;
    }
}
