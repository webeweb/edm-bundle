<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Controller\DocumentController;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\Tests\AbstractWebTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\TestFixtures;

/**
 * Document controller test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Controller
 */
class DocumentControllerTest extends AbstractWebTestCase {

    /**
     * Tests deleteAction()
     *
     * @return void
     */
    public function testDeleteAction(): void {

        $client = $this->client;

        $client->request("GET", "/document/delete/10");
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index/1", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests deleteAction()
     *
     * @return void
     */
    public function testDeleteActionWithForeignKeyConstraintViolationException(): void {

        $client = $this->client;

        $client->request("GET", "/document/delete/1");
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests downloadAction()
     *
     * @return void
     */
    public function testDownloadAction(): void {

        $client = $this->client;

        $client->request("GET", "/document/download/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/zip", $client->getResponse()->headers->get("Content-Type"));

        $this->assertRegExp('/attachement; filename="[0-9.\-]{1,}_Home\.zip"$/', $client->getResponse()->headers->get("Content-Disposition"));
    }

    /**
     * Tests editAction()
     *
     * @return void
     */
    public function testEditAction(): void {

        $client = $this->client;

        $crawler = $client->request("GET", "/document/edit/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            WBWEDMExtension::EXTENSION_ALIAS . "_document[name]" => "Home",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests indexAction()
     *
     * @return void
     */
    public function testIndexAction(): void {

        $client = $this->client;

        $client->request("GET", "/document/index");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));
    }

    /**
     * Tests indexAction()
     *
     * @return void
     */
    public function testIndexActionWithParameters(): void {

        $parameters = TestFixtures::getPOSTData();

        $client = $this->client;

        $client->request("POST", "/document/index/1", $parameters, [], ["HTTP_X-Requested-With" => "XMLHttpRequest"]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get("Content-Type"));

        // Check the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(8, $res["data"]);

        //$this->assertArrayHasKey("DT_RowId", $res["data"][0]);
        //$this->assertArrayHasKey("DT_RowClass", $res["data"][0]);
        //$this->assertArrayHasKey("DT_RowData", $res["data"][0]);

        $this->assertStringContainsString("Applications", $res["data"][0]["name"]);
        $this->assertStringContainsString("Desktop", $res["data"][1]["name"]);
        $this->assertStringContainsString("Documents", $res["data"][2]["name"]);
        $this->assertStringContainsString("Downloads", $res["data"][3]["name"]);
        $this->assertStringContainsString("Music", $res["data"][4]["name"]);
        $this->assertStringContainsString("Pictures", $res["data"][5]["name"]);
        $this->assertStringContainsString("Public", $res["data"][6]["name"]);
        $this->assertStringContainsString("Templates", $res["data"][7]["name"]);
    }

    /**
     * Tests moveAction()
     *
     * @return void
     */
    public function testMoveAction(): void {

        $client = $this->client;

        $crawler = $client->request("GET", "/document/move/9");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            WBWEDMExtension::EXTENSION_ALIAS . "_document_move[parent]" => "8",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index/8", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests newAction()
     *
     * @return void
     */
    public function testNewAction(): void {

        $client = $this->client;

        $crawler = $client->request("GET", "/document/new");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            WBWEDMExtension::EXTENSION_ALIAS . "_document[name]" => "GitHub",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests uploadAction()
     *
     * @return void
     */
    public function testUploadAction(): void {

        $path = realpath(__DIR__ . "/../Fixtures/Model/TestDocument.php");

        // Set an Uploaded file mock.
        $upload = new UploadedFile($path, "TestDocument.php", "application/php", 604);

        $client = $this->client;

        $crawler = $client->request("GET", "/document/upload");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            WBWEDMExtension::EXTENSION_ALIAS . "_document_upload[uploadedFile]" => $upload,
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests __construct()
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertEquals("wbw.edm.controller.document", DocumentController::SERVICE_NAME);
    }
}
