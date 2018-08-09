<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Event;

use PHPUnit_Framework_TestCase;
use WBW\Bundle\EDMBundle\Event\DocumentEvents;

/**
 * Document events test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Event
 * @final
 */
final class DocumentEventsTest extends PHPUnit_Framework_TestCase {

    /**
     * Tests __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $this->assertEquals("webeweb.edm.event.directory.delete", DocumentEvents::DIRECTORY_DELETE);
        $this->assertEquals("webeweb.edm.event.directory.download", DocumentEvents::DIRECTORY_DOWNLOAD);
        $this->assertEquals("webeweb.edm.event.directory.edit", DocumentEvents::DIRECTORY_EDIT);
        $this->assertEquals("webeweb.edm.event.directory.move", DocumentEvents::DIRECTORY_MOVE);
        $this->assertEquals("webeweb.edm.event.directory.new", DocumentEvents::DIRECTORY_NEW);
        $this->assertEquals("webeweb.edm.event.directory.open", DocumentEvents::DIRECTORY_OPEN);
        $this->assertEquals("webeweb.edm.event.document.delete", DocumentEvents::DOCUMENT_DELETE);
        $this->assertEquals("webeweb.edm.event.document.download", DocumentEvents::DOCUMENT_DOWNLOAD);
        $this->assertEquals("webeweb.edm.event.document.edit", DocumentEvents::DOCUMENT_EDIT);
        $this->assertEquals("webeweb.edm.event.document.move", DocumentEvents::DOCUMENT_MOVE);
        $this->assertEquals("webeweb.edm.event.document.upload", DocumentEvents::DOCUMENT_UPLOAD);
    }

}
