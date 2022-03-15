<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2020 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Navigation;

use WBW\Bundle\EDMBundle\Navigation\BreadcrumbNodes;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Library\Symfony\Assets\Navigation\BreadcrumbNode;
use WBW\Library\Symfony\Assets\NavigationNodeInterface;

/**
 * Breadcrumb nodes test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Navigation
 */
class BreadcrumbNodesTest extends AbstractTestCase {

    /**
     * Tests getFontAwesomeBreadcrumbNodes()
     *
     * @return void
     */
    public function testGetFontAwesomeBreadcrumbNodes(): void {

        $res = BreadcrumbNodes::getFontAwesomeBreadcrumbNodes();
        $this->assertCount(4, $res);

        $this->assertInstanceOf(BreadcrumbNode::class, $res[0]);
        $this->assertEquals("navigation.node.action.new", $res[0]->getLabel());
        $this->assertEquals("fa:plus", $res[0]->getIcon());
        $this->assertEquals("wbw_edm_document_new", $res[0]->getUri());
        $this->assertEquals(NavigationNodeInterface::MATCHER_ROUTER, $res[0]->getMatcher());

        $this->assertInstanceOf(BreadcrumbNode::class, $res[1]);
        $this->assertEquals("navigation.node.action.edit", $res[1]->getLabel());
        $this->assertEquals("fa:pen", $res[1]->getIcon());
        $this->assertEquals("wbw_edm_document_edit", $res[1]->getUri());
        $this->assertEquals(NavigationNodeInterface::MATCHER_ROUTER, $res[1]->getMatcher());

        $this->assertInstanceOf(BreadcrumbNode::class, $res[2]);
        $this->assertEquals("label.move", $res[2]->getLabel());
        $this->assertEquals("fa:arrows-alt", $res[2]->getIcon());
        $this->assertEquals("wbw_edm_document_move", $res[2]->getUri());
        $this->assertEquals(NavigationNodeInterface::MATCHER_ROUTER, $res[2]->getMatcher());

        $this->assertInstanceOf(BreadcrumbNode::class, $res[3]);
        $this->assertEquals("label.upload", $res[3]->getLabel());
        $this->assertEquals("fa:upload", $res[3]->getIcon());
        $this->assertEquals("wbw_edm_dropzone_upload", $res[3]->getUri());
        $this->assertEquals(NavigationNodeInterface::MATCHER_ROUTER, $res[3]->getMatcher());
    }
}
