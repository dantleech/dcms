<?php

namespace Dcms\Bundle\CoreBundle\NodeFinder;

/**
 * Node finder interface.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface NodeFinder
{
    /**
     * Find the node with the given relative path.
     *
     * @param string $relativePath
     */
    public function findNode($relativePath);
}
