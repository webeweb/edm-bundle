<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TestKernel;

/**
 * Abstract EDM web test case.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests
 * @abstract
 */
abstract class AbstractWebTestCase extends WebTestCase {

    /**
     * {@inheritdoc}
     */
    protected static function getKernelClass() {
        require_once __DIR__ . "/Fixtures/App/TestKernel.php";
        return TestKernel::class;
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

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

}
