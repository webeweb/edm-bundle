<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Tests\Provider;

use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\DocumentIconProvider;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Document icon provider test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Provider
 */
class DocumentIconProviderTest extends AbstractTestCase {

    /**
     * Test getIcon()
     *
     * @return void
     */
    public function testGetIcon(): void {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DOCUMENT);
        $document->setMimeType("application/x-php");

        $obj = new DocumentIconProvider();

        $this->assertEquals("application-x-php.svg", $obj->getIcon($document));
    }

    /**
     * Test getIconAsset()
     *
     * @return void
     */
    public function testGetIconAsset(): void {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DIRECTORY);

        $obj = new DocumentIconProvider();

        $this->assertStringContainsString("folder.svg", $obj->getIconAsset($document));
    }

    /**
     * Test getIcon()
     *
     * @return void
     */
    public function testGetIconWithDirectory(): void {

        // Set a Document mock.
        $document = new Document();
        $document->setType(DocumentInterface::TYPE_DIRECTORY);

        $obj = new DocumentIconProvider();

        $this->assertEquals("folder.svg", $obj->getIcon($document));
    }

    /**
     * Test __construct()
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertEquals("wbw.edm.provider.document_icon", DocumentIconProvider::SERVICE_NAME);

        $obj = new DocumentIconProvider();

        $this->assertNotNull($obj->getMimeTypeImageProvider());
    }
}
