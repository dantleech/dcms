<?php

namespace Dcms\Bundle\CoreBundle\Site;

use PHPCR\NodeInterface;
use PHPCR\Util\PathHelper;

class Site
{
    protected $siteNode;

    public function __construct(NodeInterface $siteNode)
    {
        $this->siteNode = $siteNode;
    }

    public function getNode()
    {
        return $this->siteNode;
    }

    public function getPath()
    {
        return $this->siteNode->getPath();
    }
}
