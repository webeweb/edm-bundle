<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Entity;

use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Document test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Entity
 */
class DocumentTest extends AbstractTestCase {

    /**
     * Tests the getChoiceLabel() method.
     *
     * @return void
     */
    public function testGetChoiceLabel() {

        $obj = new Document();

        $this->assertNull($obj->getChoiceLabel());

        $obj->setName("name");
        $this->assertEquals("name", $obj->getChoiceLabel());
    }

    /**
     * Tests __construct() method.
     *
     * @return void
     */
    public function test__construct() {

        $obj = new Document();

        $this->assertNull($obj->getChoiceLabel());
    }
}
