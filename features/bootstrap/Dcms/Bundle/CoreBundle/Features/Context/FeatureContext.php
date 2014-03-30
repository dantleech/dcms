<?php

namespace Dcms\Bundle\CoreBundle\Features\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use PHPCR\Util\NodeHelper;
use PHPCR\Util\PathHelper;

/**
 * Behat context class.
 */
class FeatureContext extends MinkContext implements KernelAwareContext, SnippetAcceptingContext
{
    use KernelDictionary;

    protected $session;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        $doctrine = $this->getContainer()->get('doctrine_phpcr');
        $session = $doctrine->getConnection();
        $this->session = $session;

        NodeHelper::purgeWorkspace($session);
        $rootNode = $session->getRootNode();
        $rootNode->addNode('dcms', 'dcms:root');
        $session->save();
    }

    /**
     * @Given there is a host :arg1 for site :arg2
     */
    public function thereIsAHostForSite($arg1, $arg2)
    {
        $hosts = $this->session->getNode('/dcms/hosts');
        $hostNode = $hosts->addNode($arg1, 'dcms:host');
        $hostNode->setProperty('site', $arg2);
        $this->session->save();
    }

    protected function createNode($path, $type)
    {
        $parentNodePath = PathHelper::getParentPath($path);
        $nodeName = basename($path);
        $parentNode = $this->session->getNode($parentNodePath);
        $node = $parentNode->addNode($nodeName, $type);

        return $node;
    }

    /**
     * @Given there is a node at :arg1 of type :arg2
     */
    public function thereIsANodeOfTypeAt($arg1, $arg2)
    {
        $this->createnode($arg1, $arg2);
        $this->session->save();
    }

    /**
     * @Given there is a node at :arg1 of type :arg2 with properties:
     */
    public function thereIsANodeOfTypeAtWithProperties($arg1, $arg2, TableNode $table)
    {
        $node = $this->createnode($arg1, $arg2);
        foreach ($table->getRows() as $row) {
            $node->setProperty($row[0], $row[1]);
        }
        $this->session->save();
    }
}
