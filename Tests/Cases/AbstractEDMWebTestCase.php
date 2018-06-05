<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Cases;

use Doctrine\ORM\Tools\SchemaTool;
use WBW\Bundle\BootstrapBundle\Tests\Cases\AbstractBootstrapWebTestCase;

/**
 * Abstract EDMweb test case.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Cases
 * @abstract
 */
abstract class AbstractEDMWebTestCase extends AbstractBootstrapWebTestCase {

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

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
