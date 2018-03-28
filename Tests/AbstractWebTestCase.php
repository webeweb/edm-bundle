<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Abstract web test case.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests
 * @abstract
 */
abstract class AbstractWebTestCase extends WebTestCase {

    /**
     * {@inheritdoc}
     */
    final public static function setUpBeforeClass() {

        // Initialize and boot the kernel.
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        // Get the entity manager.
        $em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");

        // Get all entities.
        $entities = $em->getMetadataFactory()->getAllMetadata();

        // Initialize the database.
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($entities);
    }

    /**
     * {@inheritdoc}
     */
    final protected function tearDown() {

        // Shutdown the kernel.
        static::$kernel->shutdown();
    }

}
