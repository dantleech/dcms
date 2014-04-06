<?php

namespace Dcms\Bundle\CoreBundle\Config;

class DcmsConfig
{
    public function getHostsPath()
    {
        return '/dcms/hosts';
    }

    public function getSitesPath()
    {
        return '/dcms/sites';
    }

    public function getFallbackSite()
    {
        return 'dantleech';
    }

    public function getEndpointFolderName()
    {
        return 'endpoints';
    }
}
