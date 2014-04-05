<?php

namespace Dcms\Bundle\CoreBundle\Mental;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Mental Container
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class MentalContainer
{
    /**
     * Associative mental array
     *
     * @var Mental[]
     */
    protected $mentals = array();

    /**
     * Register a new mental
     *
     * @param Mental $mental
     */
    public function registerMental(Mental $mental)
    {
        $targetNodeTypeName = $mental->getTargetNodeTypeName();

        if (isset($this->mentals[$targetNodeTypeName])) {
            throw new Exception\MentalException(sprintf(
                'Mental with name "%s" already registered',
                $targetNodeTypeName
            ));
        }

        $this->mentals[$targetNodeTypeName] = $mental;
    }

    /**
     * {@inheritDoc}
     */
    public function getMental($mentalNodeTypeName)
    {
        if (!isset($this->mentals[$mentalNodeTypeName])) {
            throw new Exception\MentalException(sprintf(
                'Trying to retrieve unknown mental "%s"',
                $mentalNodeTypeName
            ));
        }

        return $this->mentals[$mentalNodeTypeName];
    }
}
