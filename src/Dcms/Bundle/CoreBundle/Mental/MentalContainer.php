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
        $mentalName = $mental->getName();

        if (isset($this->mentals[$mentalName])) {
            throw new MentalException(sprintf(
                'Mental with name "%s" already registered',
                $mentalName
            ));
        }

        $this->mentals[$mental->getName()] = $mental;
    }

    /**
     * {@inheritDoc}
     */
    public function getMental($mentalName)
    {
        if (!isset($this->mentals[$mentalName])) {
            throw new MentalException(sprintf(
                'Trying to retrieve unknown mental "%s"',
                $mentalName
            ));
        }

        return $this->mentals[$mentalName];
    }
}
