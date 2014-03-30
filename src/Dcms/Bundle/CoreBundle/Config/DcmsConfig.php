<?php

namespace Dcms\Bundle\CoreBundle\Config;

class DcmsConfig
{
    public function getHostsPath()
    {
        return '/dcms/hosts';
    }

    public function getDefaultHost()
    {
        return 'default.dom';
    }

    public function getEndpointFolderName()
    {
        return 'endpoints';
    }
}
