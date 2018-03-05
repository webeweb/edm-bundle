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
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;

/**
 * Document event test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Event
 * @final
 */
final class DocumentEventTest extends PHPUnit_Framework_TestCase {

    /**
     * Tests __construct() method.
     *
     * @return void.
     */
    public function testConstructor() {

        $arg = new Document();
        $obj = new DocumentEvent("name", $arg);

        $this->assertEquals($arg, $obj->getDocument());
        $this->assertEquals("name", $obj->getName());
    }

}
