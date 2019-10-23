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
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
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
        $this->assertCount(0, $res);
    }

    /**
     * Tests the getEntity() method.
     *
     * @return void
     */
    public function testGetEntity() {

        $obj = $this->documentDataTablesProvider;

        $this->assertEquals(DocumentInterface::class, $obj->getEntity());
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

        $this->assertEquals("@WBWEDM/Document/index/content.html.twig", $obj->getView());
    }
}
