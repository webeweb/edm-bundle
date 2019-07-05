<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests;

use WBW\Bundle\EDMBundle\WBWEDMEvents;

/**
 * EDM events test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests
 */
class WBWEDMEventsTest extends AbstractTestCase {

    /**
     * Tests __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $this->assertEquals("wbw.edm.event.delete_directory", WBWEDMEvents::DIRECTORY_DELETE);
        $this->assertEquals("wbw.edm.event.download_directory", WBWEDMEvents::DIRECTORY_DOWNLOAD);
        $this->assertEquals("wbw.edm.event.edit_directory", WBWEDMEvents::DIRECTORY_EDIT);
        $this->assertEquals("wbw.edm.event.move_directory", WBWEDMEvents::DIRECTORY_MOVE);
        $this->assertEquals("wbw.edm.event.new_directory", WBWEDMEvents::DIRECTORY_NEW);
        $this->assertEquals("wbw.edm.event.open_directory", WBWEDMEvents::DIRECTORY_OPEN);

        $this->assertEquals("wbw.edm.event.delete_document", WBWEDMEvents::DOCUMENT_DELETE);
        $this->assertEquals("wbw.edm.event.download_document", WBWEDMEvents::DOCUMENT_DOWNLOAD);
        $this->assertEquals("wbw.edm.event.edit_document", WBWEDMEvents::DOCUMENT_EDIT);
        $this->assertEquals("wbw.edm.event.move_document", WBWEDMEvents::DOCUMENT_MOVE);
        $this->assertEquals("wbw.edm.event.upload_document", WBWEDMEvents::DOCUMENT_UPLOAD);
    }
}
