<?php

namespace Dcms\Bundle\CoreBundle\Mental;

use PHPCR\NodeInterface;

/**
 * Mental interface
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface Mental
{
    /**
     * Return the node type name which corresponds to the
     * primary node type of nodes for which this mental will
     * handle.
     *
     * @return string
     */
    public function getTargetNodeTypeName();

    /**
     * Return route defaults for the given routeNode
     *
     * @param NodeInterface $routeNode
     */
    public function getEndpointDefaults(NodeInterface $routeNode);
}
