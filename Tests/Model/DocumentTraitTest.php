<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2022 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Model;

use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Model\TestDocumentTrait;

/**
 * Document trait test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Model
 */
class DocumentTraitTest extends AbstractTestCase {

    /**
     * Test setDocument()
     *
     * @return void
     */
    public function testSetDocument(): void {

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new TestDocumentTrait();

        $obj->setDocument($document);
        $this->assertSame($document, $obj->getDocument());
    }
}
