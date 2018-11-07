<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Helper;

use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\TestFixtures;

/**
 * Document helper test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Helper
 */
class DocumentHelperTest extends AbstractFrameworkTestCase {

    /**
     * Tests the getFilename() method.
     *
     * @return void
     */
    public function testGetFilename() {

        $obj = new Document();

        $obj->setName("directory");
        $obj->setType(Document::TYPE_DIRECTORY);
        $this->assertEquals("directory", DocumentHelper::getFilename($obj));

        $obj->setName("filename");
        $obj->setExtension("ext");
        $obj->setType(Document::TYPE_DOCUMENT);
        $this->assertEquals("filename.ext", DocumentHelper::getFilename($obj));
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
        $this->assertEquals("directory", DocumentHelper::getPathname($obj1));

        $obj2->setName("filename");
        $obj2->setExtension("ext");
        $obj2->setType(Document::TYPE_DOCUMENT);
        $this->assertEquals("directory/filename.ext", DocumentHelper::getPathname($obj2));
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

        $this->assertEquals([$obj1], DocumentHelper::getPaths($obj1));
        $this->assertEquals([$obj1, $obj2], DocumentHelper::getPaths($obj2));
    }

    /**
     * Tests the toArray() method.
     *
     * @return void
     */
    public function testToArray() {

        $arg = TestFixtures::getDocuments();

        $res = DocumentHelper::toArray($arg);

        $this->assertCount(9, $res);

        $this->assertSame($arg->getChildrens()[0], $res[0]);
        $this->assertSame($arg->getChildrens()[1], $res[1]);
        $this->assertSame($arg->getChildrens()[2], $res[2]);
        $this->assertSame($arg->getChildrens()[3], $res[3]);
        $this->assertSame($arg->getChildrens()[4], $res[4]);
        $this->assertSame($arg->getChildrens()[5], $res[5]);
        $this->assertSame($arg->getChildrens()[6], $res[6]);
        $this->assertSame($arg->getChildrens()[7], $res[7]);
        $this->assertSame($arg->getChildrens()[8], $res[8]);
    }

}
