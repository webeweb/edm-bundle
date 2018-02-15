<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Utility;

use PHPUnit_Framework_TestCase;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Utility\DocumentUtility;

/**
 * Document utility test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Utility
 * @final
 */
final class DocumentUtilityTest extends PHPUnit_Framework_TestCase {

    /**
     * Tests the getFilename() method.
     *
     * @return void
     */
    public function testGetFilename() {

        $obj = new Document();

        $obj->setName("name");
        $obj->setType(Document::TYPE_DIRECTORY);
        $this->assertEquals("name", DocumentUtility::getFilename($obj));

        $obj->setExtension("extension");
        $obj->setType(Document::TYPE_DOCUMENT);
        $this->assertEquals("name.extension", DocumentUtility::getFilename($obj));
    }

    /**
     * Tests the getPaths() method.
     *
     * @return void
     */
    public function testGetPaths() {

        $obj = new Document();
        $arg = new Document();

        $obj->addChildren($arg);

        $this->assertEquals([$obj], DocumentUtility::getPaths($obj));
        $this->assertEquals([$obj, $arg], DocumentUtility::getPaths($arg));
    }

}
