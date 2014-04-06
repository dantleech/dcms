<?php

namespace Dcms\Bundle\CoreBundle\Site;

use Dcms\Bundle\CoreBundle\Site\Site;

class SiteContext
{
    protected $site;

    /**
     * Return the current site node
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set the current site
     *
     * @param Site
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
    }

    public function getAbsPathFor($relPath)
    {
        $basePath = $this->getSite()->getNode()->getPath();
        return $basePath . '/' . $relPath;
    }

    /**
     * Return the paths of this site and any ancestoral sites
     *
     * @return string[]
     */
    public function getSiteCascadePaths()
    {
        $sitePath = $this->site->getPath();
        $paths[] = $sitePath;

        while ($index = strrpos($sitePath, 'sites')) {
            $sitePath = substr($sitePath, 0, $index - 1);
            if ($sitePath) {
                $paths[] = $sitePath;
            }
        }

        return $paths;
    }

}
