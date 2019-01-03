<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Twig\Extension;

use Twig_Node;
use Twig_SimpleFunction;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Tests\AbstractFrameworkTestCase;
use WBW\Bundle\EDMBundle\Twig\Extension\EDMTwigExtension;

/**
 * EDM Twig extension test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Twig\Extension
 */
class EDMTwigExtensionTest extends AbstractFrameworkTestCase {

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a Router mock.
        $this->router->expects($this->any())->method("generate")->willReturnCallback(function($route, array $args = []) {
            return $route;
        });

        // Set a Translator mock.
        $this->translator->expects($this->any())->method("trans")->willReturnCallback(function($id, array $parameters = [], $domain = null, $locale = null) {
            return $id;
        });
    }

    /**
     * Tests the edmLinkFunction() method.
     *
     * @return void
     */
    public function testEdmLinkFunctionWithDirectory() {

        // Set a Document mock.
        $directory = new Document();
        $directory
            ->setName("phpunit")
            ->setType(Document::TYPE_DIRECTORY);

        $obj = new EDMTwigExtension($this->router, $this->translator);

        $res = '<a class="btn btn-link" href="edm_directory_open" title="label.open phpunit" data-toggle="tooltip" data-placement="right">phpunit</a>';
        $this->assertEquals($res, $obj->edmLinkFunction($directory));

    }

    /**
     * Tests the edmLinkFunction() method.
     *
     * @return void
     */
    public function testEdmLinkFunctionWithDocument() {

        // Set a Document mock.
        $document = new Document();
        $document
            ->setExtension("txt")
            ->setName("phpunit")
            ->setType(Document::TYPE_DOCUMENT);

        $obj = new EDMTwigExtension($this->router, $this->translator);

        $res = 'phpunit.txt';
        $this->assertEquals($res, $obj->edmLinkFunction($document));
    }

    /**
     * Tests the edmPathFunction() method.
     *
     * @return void
     */
    public function testEdmPathFunction() {

        $obj = new EDMTwigExtension($this->router, $this->translator);

        $this->assertEquals("/", $obj->edmPathFunction(null));
        $this->assertEquals("/phpunit", $obj->edmPathFunction((new Document())->setType(Document::TYPE_DIRECTORY)->setName("phpunit")));
    }

    /**
     * Tests the edmSizeFunction() method.
     *
     * @return void
     */
    public function testEdmSizeFunction() {

        $obj = new EDMTwigExtension($this->router, $this->translator);

        $this->assertEquals('<span title="0.00 B" data-toggle="tooltip" data-placement="bottom">0 label.items</span>', $obj->edmSizeFunction((new Document())->setType(Document::TYPE_DIRECTORY)));
        $this->assertEquals('<span title="1.00 KB" data-toggle="tooltip" data-placement="bottom">1.00 KB</span>', $obj->edmSizeFunction((new Document())->setType(Document::TYPE_DOCUMENT)->setSize(1000)));
    }

    /**
     * Tests the getFunctions() method.
     *
     * @return void
     */
    public function testGetFunctions() {

        $obj = new EDMTwigExtension($this->router, $this->translator);

        $res = $obj->getFunctions();
        $this->assertCount(3, $res);

        $this->assertInstanceOf(Twig_SimpleFunction::class, $res[0]);
        $this->assertEquals("edmLink", $res[0]->getName());
        $this->assertEquals([$obj, "edmLinkFunction"], $res[0]->getCallable());
        $this->assertEquals(["html"], $res[0]->getSafe(new Twig_Node()));

        $this->assertInstanceOf(Twig_SimpleFunction::class, $res[1]);
        $this->assertEquals("edmPath", $res[1]->getName());
        $this->assertEquals([$obj, "edmPathFunction"], $res[1]->getCallable());
        $this->assertEquals([], $res[1]->getSafe(new Twig_Node()));

        $this->assertInstanceOf(Twig_SimpleFunction::class, $res[2]);
        $this->assertEquals("edmSize", $res[2]->getName());
        $this->assertEquals([$obj, "edmSizeFunction"], $res[2]->getCallable());
        $this->assertEquals(["html"], $res[2]->getSafe(new Twig_Node()));
    }

}
