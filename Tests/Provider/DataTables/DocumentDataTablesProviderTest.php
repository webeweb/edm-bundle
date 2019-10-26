<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Provider\DataTables;

use WBW\Bundle\BootstrapBundle\Twig\Extension\CSS\ButtonTwigExtension;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Provider\DataTables\DocumentDataTablesProvider;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Document DataTables provider test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Provider\DataTables
 */
class DocumentDataTablesProviderTest extends AbstractTestCase {

    /**
     * Button Twig extension.
     *
     * @var ButtonTwigExtension
     */
    private $buttonTwigExtension;

    /**
     * Document DataTables provider.
     *
     * @var DocumentDataTablesProvider
     */
    private $documentDataTablesProvider;

    /**
     * {@inheritDoc}
     */
    protected function setUp() {
        parent::setUp();

        // Set a Button Twig extension mock.
        $this->buttonTwigExtension = new ButtonTwigExtension($this->twigEnvironment);

        // Set a Document DataTables provider.
        $this->documentDataTablesProvider = new DocumentDataTablesProvider($this->router, $this->translator, $this->buttonTwigExtension);
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $this->assertEquals("wbw-edm-document", DocumentDataTablesProvider::DATATABLES_NAME);
        $this->assertEquals("wbw.edm.provider.datatables.document", DocumentDataTablesProvider::SERVICE_NAME);

        $obj = $this->documentDataTablesProvider;

        $this->assertSame($this->buttonTwigExtension, $obj->getButtonTwigExtension());
        $this->assertSame($this->router, $obj->getRouter());
        $this->assertSame($this->translator, $obj->getTranslator());
    }

    /**
     * Tests the getColumns() method.
     *
     * @return void
     */
    public function testGetColumns() {

        $obj = $this->documentDataTablesProvider;

        $res = $obj->getColumns();
        $this->assertCount(5, $res);

        $this->assertEquals("name", $res[0]->getData());
        $this->assertEquals("label.name", $res[0]->getName());

        $this->assertEquals("size", $res[1]->getData());
        $this->assertEquals("label.size", $res[1]->getName());
        $this->assertEquals("111px", $res[1]->getWidth());

        $this->assertEquals("updatedAt", $res[2]->getData());
        $this->assertEquals("label.updated_at", $res[2]->getName());
        $this->assertEquals("111px", $res[2]->getWidth());

        $this->assertEquals("type", $res[3]->getData());
        $this->assertEquals("label.type", $res[3]->getName());
        $this->assertEquals("185px", $res[3]->getWidth());
        $this->assertFalse($res[3]->getSearchable());

        $this->assertEquals("actions", $res[4]->getData());
        $this->assertEquals("label.actions", $res[4]->getName());
        $this->assertEquals("185px", $res[4]->getWidth());
        $this->assertFalse($res[4]->getOrderable());
        $this->assertFalse($res[4]->getSearchable());
    }

    /**
     * Tests the getEntity() method.
     *
     * @return void
     */
    public function testGetEntity() {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals(Document::class, $obj->getEntity());
    }

    /**
     * Tests the getName() method.
     *
     * @return void
     */
    public function testGetName() {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals(DocumentDataTablesProvider::DATATABLES_NAME, $obj->getName());
    }

    /**
     * Tests the getOptions() method.
     *
     * @return void
     */
    public function testGetOptions() {

        $obj = $this->documentDataTablesProvider;

        $res = $obj->getOptions();
        $this->assertTrue($res->getOption("responsive"));
        $this->assertEquals(1000, $res->getOption("searchDelay"));

        $this->assertFalse($res->getOption("bPaginate"));
        $this->assertEquals([[4, "desc"], [1, "asc"]], $res->getOption("order"));
    }

    /**
     * Tests the getPrefix() method.
     *
     * @return void
     */
    public function testGetPrefix() {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals("d", $obj->getPrefix());
    }

    /**
     * Tests the getView() method.
     *
     * @return void
     */
    public function testGetView() {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals("@WBWEDM/Document/index.html.twig", $obj->getView());
    }

    /**
     * Tests the renderColumn() method.
     *
     * @return void
     */
    public function testRenderColumn() {

        // Set a Document mock.
        $document = new Document();
        $document->setExtension("php");
        $document->setMimeType("application/octet-stream");
        $document->setName("document");
        $document->setSize(1024);
        $document->setType(Document::TYPE_DOCUMENT);

        $obj = $this->documentDataTablesProvider;

        $col = $obj->getColumns();

        $this->assertEquals("document.php", $obj->renderColumn($col[0], $document));
        $this->assertEquals("1.00 Kio", $obj->renderColumn($col[1], $document));
        $this->assertEquals(null, $obj->renderColumn($col[2], $document));
        $this->assertEquals(null, $obj->renderColumn($col[3], $document));
        $this->assertEquals(null, $obj->renderColumn($col[4], $document));

        $document->setType(Document::TYPE_DIRECTORY);
        $this->assertEquals("1.00 Kio<br/>label.items_count", $obj->renderColumn($col[1], $document));
    }

    /**
     * Tests the renderRow() method.
     *
     * @return void
     */
    public function testRenderRow() {

        // Set a Document mock.
        $document = new Document();

        $obj = $this->documentDataTablesProvider;

        $this->assertNull($obj->renderRow(null, null, null));
    }
}
