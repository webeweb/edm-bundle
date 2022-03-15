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

use DateTime;
use Exception;
use WBW\Bundle\BootstrapBundle\Twig\Extension\CSS\ButtonTwigExtension;
use WBW\Bundle\CoreBundle\Tests\TestCaseHelper;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\DataTables\DocumentDataTablesProvider;
use WBW\Bundle\EDMBundle\Provider\DocumentIconProvider;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;

/**
 * Document DataTables provider test.
 *
 * @author webeweb <https://github.com/webeweb>
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
    protected function setUp(): void {
        parent::setUp();

        // Set generate() callback.
        $generate = TestCaseHelper::getRouterGenerateFunction();

        // Set the Router mock.
        $this->router->expects($this->any())->method("generate")->willReturnCallback($generate);

        // Set a Button Twig extension mock.
        $this->buttonTwigExtension = new ButtonTwigExtension($this->twigEnvironment);

        // Set a Document DataTables provider.
        $this->documentDataTablesProvider = new DocumentDataTablesProvider($this->router, $this->translator, $this->buttonTwigExtension);
        $this->documentDataTablesProvider->setDocumentIconProvider(new DocumentIconProvider());
        $this->documentDataTablesProvider->setKernelEventListener($this->kernelEventListener);
    }

    /**
     * Tests getColumns()
     *
     * @return void
     */
    public function testGetColumns(): void {

        $obj = $this->documentDataTablesProvider;

        $res = $obj->getColumns();
        $this->assertCount(5, $res);

        $this->assertEquals("name", $res[0]->getData());
        $this->assertEquals("label.name", $res[0]->getName());

        $this->assertEquals("size", $res[1]->getData());
        $this->assertEquals("label.size", $res[1]->getName());
        $this->assertEquals("60px", $res[1]->getWidth());

        $this->assertEquals("updatedAt", $res[2]->getData());
        $this->assertEquals("label.updated_at", $res[2]->getName());
        $this->assertEquals("120px", $res[2]->getWidth());

        $this->assertEquals("type", $res[3]->getData());
        $this->assertEquals("label.type", $res[3]->getName());
        $this->assertEquals("160px", $res[3]->getWidth());
        $this->assertFalse($res[3]->getSearchable());

        $this->assertEquals("actions", $res[4]->getData());
        $this->assertEquals("label.actions", $res[4]->getName());
        $this->assertEquals("160px", $res[4]->getWidth());
        $this->assertFalse($res[4]->getOrderable());
        $this->assertFalse($res[4]->getSearchable());
    }

    /**
     * Tests getEntity()
     *
     * @return void
     */
    public function testGetEntity(): void {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals(Document::class, $obj->getEntity());
    }

    /**
     * Tests getName()
     *
     * @return void
     */
    public function testGetName(): void {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals(DocumentDataTablesProvider::DATATABLES_NAME, $obj->getName());
    }

    /**
     * Tests getOptions()
     *
     * @return void
     */
    public function testGetOptions(): void {

        $obj = $this->documentDataTablesProvider;

        $res = $obj->getOptions();
        $this->assertTrue($res->getOption("responsive"));
        $this->assertEquals(1000, $res->getOption("searchDelay"));

        $this->assertFalse($res->getOption("bPaginate"));
        $this->assertEquals([[3, "desc"], [0, "asc"]], $res->getOption("order"));
    }

    /**
     * Tests getPrefix()
     *
     * @return void
     */
    public function testGetPrefix(): void {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals("d", $obj->getPrefix());
    }

    /**
     * Tests getUrl()
     *
     * @return void
     */
    public function testGetUrl(): void {

        // Set the Router mock.
        $this->router->expects($this->any())->method("generate")->willReturnCallback(function($name) {
            return $name;
        });

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals("wbw_edm_document_index", $obj->getUrl());
    }

    /**
     * Tests getView()
     *
     * @return void
     */
    public function testGetView(): void {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals("@WBWEDM/Document/index.html.twig", $obj->getView());
    }

    /**
     * Tests renderColumn()
     *
     * @return void
     */
    public function testRenderColumn(): void {

        // Set a Document mock.
        $document = new Document();
        $document->setExtension("php");
        $document->setMimeType("application/octet-stream");
        $document->setName("document");
        $document->setSize(1024);
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $obj = $this->documentDataTablesProvider;

        $btn = [
            '<a class="btn btn-default btn-xs" title="label.edit" href="wbw_edm_document_edit" data-toggle="tooltip" data-placement="top"><i class="fa fa-pen"></i></a>',
            '<a class="btn btn-danger btn-xs" title="label.delete" href="wbw_edm_document_delete" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i></a>',
            '<a class="btn btn-info btn-xs" title="label.download" href="wbw_edm_document_download" data-toggle="tooltip" data-placement="top"><i class="fa fa-download"></i></a>',
            '<a class="btn btn-default btn-xs" title="label.move" href="wbw_edm_document_move" data-toggle="tooltip" data-placement="top"><i class="fa fa-arrows-alt"></i></a>',
        ];

        $col = $obj->getColumns();

        $this->assertEquals('<span class="pull-left"><img src="/bundles/wbwedm/img/application-octet-stream.svg" height="32px" /></span>document.php', $obj->renderColumn($col[0], $document));
        $this->assertRegExp('/^<span class="pull-right">1[\.,]00 Kio<\/span>$/', $obj->renderColumn($col[1], $document));
        $this->assertRegExp("/^[0-9\- :]{16}$/", $obj->renderColumn($col[2], $document));
        $this->assertEquals("application/octet-stream", $obj->renderColumn($col[3], $document));
        $this->assertEquals(implode(" ", $btn), $obj->renderColumn($col[4], $document));
    }

    /**
     * Tests renderColumn()
     *
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function testRenderColumnWithDirectory(): void {

        // Set a Document mock.
        $document = new Document();
        $document->setName("document");
        $document->setSize(1024);
        $document->setType(DocumentInterface::TYPE_DIRECTORY);
        $document->setUpdatedAt(new DateTime());

        $obj = $this->documentDataTablesProvider;

        $btn = [
            '<a class="btn btn-default btn-xs" title="label.edit" href="wbw_edm_document_edit" data-toggle="tooltip" data-placement="top"><i class="fa fa-pen"></i></a>',
            '<a class="btn btn-danger btn-xs" title="label.delete" href="wbw_edm_document_delete" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i></a>',
            '<a class="btn btn-info btn-xs" title="label.download" href="wbw_edm_document_download" data-toggle="tooltip" data-placement="top"><i class="fa fa-download"></i></a>',
            '<a class="btn btn-default btn-xs" title="label.move" href="wbw_edm_document_move" data-toggle="tooltip" data-placement="top"><i class="fa fa-arrows-alt"></i></a>',
            '<a class="btn btn-primary btn-xs" title="label.index" href="wbw_edm_document_index" data-toggle="tooltip" data-placement="top"><i class="fa fa-folder-open"></i></a>',
            '<a class="btn btn-success btn-xs" title="label.upload" href="wbw_edm_dropzone_upload" data-toggle="tooltip" data-placement="top"><i class="fa fa-upload"></i></a>',
        ];

        $col = $obj->getColumns();

        $this->assertEquals('<span class="pull-left"><img src="/bundles/wbwedm/img/folder.svg" height="32px" /></span>document<br/><span class="font-italic">label.items_count</span>', $obj->renderColumn($col[0], $document));
        $this->assertRegExp('/^<span class="pull-right">1[\.,]00 Kio<\/span>$/', $obj->renderColumn($col[1], $document));
        $this->assertRegExp("/^[0-9\- :]{16}$/", $obj->renderColumn($col[2], $document));
        $this->assertEquals("label.directory", $obj->renderColumn($col[3], $document));
        $this->assertEquals(implode(" ", $btn), $obj->renderColumn($col[4], $document));
    }

    /**
     * Tests renderRow()
     *
     * @return void
     */
    public function testRenderRow(): void {

        $obj = $this->documentDataTablesProvider;

        $this->assertNull($obj->renderRow("", null, 0));
    }

    /**
     * Tests __construct()
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertEquals("wbw-edm-document", DocumentDataTablesProvider::DATATABLES_NAME);
        $this->assertEquals("wbw.edm.provider.datatables.document", DocumentDataTablesProvider::SERVICE_NAME);

        $obj = $this->documentDataTablesProvider;

        $this->assertSame($this->buttonTwigExtension, $obj->getButtonTwigExtension());
        $this->assertSame($this->router, $obj->getRouter());
        $this->assertSame($this->translator, $obj->getTranslator());
    }
}
