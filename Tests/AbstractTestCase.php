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

use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\CoreBundle\Tests\AbstractTestCase as TestCase;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;

/**
 * Abstract test case.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests
 * @abstract
 */
abstract class AbstractTestCase extends TestCase {

    /**
     * Directory.
     *
     * @var DocumentInterface
     */
    protected $directory;

    /**
     * Document.
     *
     * @var DocumentInterface
     */
    protected $document;

    /**
     * Storage manager.
     *
     * @var StorageManager
     */
    protected $storageManager;

    /**
     * Storage provider.
     *
     * @var StorageProviderInterface
     */
    protected $storageProvider;

    /**
     * Storage provider directory.
     *
     * @var string
     */
    protected $storageProviderDirectory;

    /**
     * Uploaded file.
     *
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a Document mock.
        $this->document = new Document();
        $this->document->setType(Document::TYPE_DOCUMENT);

        // Set a Directory mock.
        $this->directory = new Document();
        $this->directory->setType(Document::TYPE_DIRECTORY);

        // Set a Storage manager mock.
        $this->storageManager = new StorageManager($this->logger);

        // Set a Storage provider mock.
        $this->storageProvider = $this->getMockBuilder(StorageProviderInterface::class)->getMock();

        // Set a storage provider directory mock.
        $this->storageProviderDirectory = getcwd() . "/Tests/Fixtures/app/var/data";

        // Set an Upload file mock.
        $this->uploadedFile = new UploadedFile(getcwd() . "/Tests/Fixtures/Model/TestDocument.bak.php", "TestDocument.php", "application/x-php", 604, true);
    }
}
