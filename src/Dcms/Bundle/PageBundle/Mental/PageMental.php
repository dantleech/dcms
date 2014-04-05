<?php

namespace Dcms\Bundle\PageBundle\Mental;

use Dcms\Bundle\CoreBundle\Mental\Mental;
use PHPCR\NodeInterface;

class PageMental implements Mental
{
    public function getTargetNodeTypeName()
    {
        return 'dcms:mental:page';
    }

    public function getEndpointDefaults(NodeInterface $routeNode)
    {
        $defaults = array(
            '_controller' => 'DcmsPageBundle:Mental:page',
            '_route' => 'foo',
            '_mental' => $routeNode->getNode('mental'),
        );

        return $defaults;
    }
}
