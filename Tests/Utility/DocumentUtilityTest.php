<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
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
 * @author webeweb <https://github.com/webeweb/>
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

        $obj->setName("directory");
        $obj->setType(Document::TYPE_DIRECTORY);
        $this->assertEquals("directory", DocumentUtility::getFilename($obj));

        $obj->setName("filename");
        $obj->setExtension("ext");
        $obj->setType(Document::TYPE_DOCUMENT);
        $this->assertEquals("filename.ext", DocumentUtility::getFilename($obj));
    }

    /**
     * Tests the getPathname() method.
     *
     * @return void
     */
    public function testGetPathname() {

        $obj1 = new Document();
        $obj2 = new Document();

        $obj1->addChildren($obj2);

        $obj1->setName("directory");
        $obj1->setType(Document::TYPE_DIRECTORY);
        $this->assertEquals("directory", DocumentUtility::getPathname($obj1));

        $obj2->setName("filename");
        $obj2->setExtension("ext");
        $obj2->setType(Document::TYPE_DOCUMENT);
        $this->assertEquals("directory/filename.ext", DocumentUtility::getPathname($obj2));
    }

    /**
     * Tests the getPaths() method.
     *
     * @return void
     */
    public function testGetPaths() {

        $obj1 = new Document();
        $obj2 = new Document();

        $obj1->addChildren($obj2);

        $this->assertEquals([$obj1], DocumentUtility::getPaths($obj1));
        $this->assertEquals([$obj1, $obj2], DocumentUtility::getPaths($obj2));
    }

}
