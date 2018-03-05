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
     * @return void.
     */
    public function testConstructor() {

        $this->assertEquals("webeweb.bundle.edmbundle.event.directory.delete", DocumentEvents::DIRECTORY_DELETE);
        $this->assertEquals("webeweb.bundle.edmbundle.event.directory.download", DocumentEvents::DIRECTORY_DOWNLOAD);
        $this->assertEquals("webeweb.bundle.edmbundle.event.directory.edit", DocumentEvents::DIRECTORY_EDIT);
        $this->assertEquals("webeweb.bundle.edmbundle.event.directory.move", DocumentEvents::DIRECTORY_MOVE);
        $this->assertEquals("webeweb.bundle.edmbundle.event.directory.new", DocumentEvents::DIRECTORY_NEW);
        $this->assertEquals("webeweb.bundle.edmbundle.event.directory.open", DocumentEvents::DIRECTORY_OPEN);
        $this->assertEquals("webeweb.bundle.edmbundle.event.document.delete", DocumentEvents::DOCUMENT_DELETE);
        $this->assertEquals("webeweb.bundle.edmbundle.event.document.download", DocumentEvents::DOCUMENT_DOWNLOAD);
        $this->assertEquals("webeweb.bundle.edmbundle.event.document.edit", DocumentEvents::DOCUMENT_EDIT);
        $this->assertEquals("webeweb.bundle.edmbundle.event.document.move", DocumentEvents::DOCUMENT_MOVE);
        $this->assertEquals("webeweb.bundle.edmbundle.event.document.upload", DocumentEvents::DOCUMENT_UPLOAD);
    }

}
