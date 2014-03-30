<?php

namespace Dcms\Bundle\CoreBundle\Mental;

/**
 * Mental interface
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface Mental
{
    public function getName();

    public function getEndpointDefaults($routeNode);
}
