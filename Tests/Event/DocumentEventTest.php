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

use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;

/**
 * Document event test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Event
 * @final
 */
final class DocumentEventTest extends AbstractFrameworkTestCase {

    /**
     * Tests __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $arg = new Document();
        $obj = new DocumentEvent("name", $arg);

        $this->assertEquals($arg, $obj->getDocument());
        $this->assertEquals("name", $obj->getEventName());
    }

}
