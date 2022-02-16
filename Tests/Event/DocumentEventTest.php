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
     * Tests setResponse()
     *
     * @return void
     */
    public function testSetResponse(): void {

        // Set the Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();
        $response = $this->getMockBuilder(Response::class)->getMock();

        $obj = new DocumentEvent("name", $document);

        $obj->setResponse($response);
        $this->assertSame($response, $obj->getResponse());
    }

    /**
     * Tests __construct() method.
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertEquals("wbw.edm.event.document.pre_delete", DocumentEvent::PRE_DELETE);
        $this->assertEquals("wbw.edm.event.document.pre_download", DocumentEvent::PRE_DOWNLOAD);
        $this->assertEquals("wbw.edm.event.document.pre_edit", DocumentEvent::PRE_EDIT);
        $this->assertEquals("wbw.edm.event.document.pre_move", DocumentEvent::PRE_MOVE);
        $this->assertEquals("wbw.edm.event.document.pre_new", DocumentEvent::PRE_NEW);

        $this->assertEquals("wbw.edm.event.document.post_delete", DocumentEvent::POST_DELETE);
        $this->assertEquals("wbw.edm.event.document.post_download", DocumentEvent::POST_DOWNLOAD);
        $this->assertEquals("wbw.edm.event.document.post_edit", DocumentEvent::POST_EDIT);
        $this->assertEquals("wbw.edm.event.document.post_move", DocumentEvent::POST_MOVE);
        $this->assertEquals("wbw.edm.event.document.post_new", DocumentEvent::POST_NEW);

        // Set a Document mock.
        $document = $this->getMockBuilder(DocumentInterface::class)->getMock();

        $obj = new DocumentEvent("name", $document);

        $this->assertEquals("name", $obj->getEventName());
        $this->assertSame($document, $obj->getDocument());
        $this->assertNull($obj->getResponse());
    }
}
