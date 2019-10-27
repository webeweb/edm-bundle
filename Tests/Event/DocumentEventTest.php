<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Event;

use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Document event test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Event
 */
class DocumentEventTest extends AbstractTestCase {

    /**
     * Tests __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new DocumentEvent("name", $document);

        $this->assertEquals("name", $obj->getEventName());
        $this->assertSame($document, $obj->getDocument());
        $this->assertNull($obj->getResponse());
    }

    /**
     * Tests the setResponse() method.
     *
     * @return void
     */
    public function testSetResponse() {

        // Set the Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();
        $response = $this->getMockBuilder(Response::class)->getMock();

        $obj = new DocumentEvent("name", $document);

        $obj->setResponse($response);
        $this->assertSame($response, $obj->getResponse());
    }
}
