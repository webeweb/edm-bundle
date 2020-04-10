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
    public function test__construct() {

        $this->assertEquals("wbw.edm.event.document.pre_delete", WBWEDMEvents::DOCUMENT_PRE_DELETE);
        $this->assertEquals("wbw.edm.event.document.pre_download", WBWEDMEvents::DOCUMENT_PRE_DOWNLOAD);
        $this->assertEquals("wbw.edm.event.document.pre_edit", WBWEDMEvents::DOCUMENT_PRE_EDIT);
        $this->assertEquals("wbw.edm.event.document.pre_move", WBWEDMEvents::DOCUMENT_PRE_MOVE);
        $this->assertEquals("wbw.edm.event.document.pre_new", WBWEDMEvents::DOCUMENT_PRE_NEW);

        $this->assertEquals("wbw.edm.event.document.post_delete", WBWEDMEvents::DOCUMENT_POST_DELETE);
        $this->assertEquals("wbw.edm.event.document.post_download", WBWEDMEvents::DOCUMENT_POST_DOWNLOAD);
        $this->assertEquals("wbw.edm.event.document.post_edit", WBWEDMEvents::DOCUMENT_POST_EDIT);
        $this->assertEquals("wbw.edm.event.document.post_move", WBWEDMEvents::DOCUMENT_POST_MOVE);
        $this->assertEquals("wbw.edm.event.document.post_new", WBWEDMEvents::DOCUMENT_POST_NEW);
    }
}
