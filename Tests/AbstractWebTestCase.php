<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests;

use DirectoryIterator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use WBW\Bundle\CoreBundle\Tests\AbstractWebTestCase as WebTestCase;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Provider\Storage\FilesystemStorageProvider;
use WBW\Bundle\EDMBundle\Tests\Fixtures\TestFixtures;

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
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        parent::setUpSchemaTool();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");

        $entities = TestFixtures::getDocuments();
        foreach ($entities as $current) {
            $em->persist($current);
        }

        $em->flush();

        /** @var FilesystemStorageProvider $fs */
        $fs = static::$kernel->getContainer()->get(FilesystemStorageProvider::SERVICE_NAME);

        foreach (new DirectoryIterator($fs->getDirectory()) as $current) {
            if (0 === preg_match("/^[0-9]{1,}(\.download)?$/", $current->getFilename())) {
                continue;
            }
            (new Filesystem())->remove($current->getPathname());
        }

        /** @var StorageManager $sm */
        $sm = static::$kernel->getContainer()->get(StorageManager::SERVICE_NAME);

        foreach ($entities as $current) {
            $sm->newDirectory($current);
        }
    }
}
