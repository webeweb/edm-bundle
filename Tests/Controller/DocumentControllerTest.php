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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Tests\AbstractWebTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\TestFixtures;

/**
 * Document controller test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Controller
 */
class DocumentControllerTest extends AbstractWebTestCase {

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");

        foreach (TestFixtures::getDocuments() as $current) {
            $em->persist($current);
        }

        $em->flush();
    }

    /**
     * Tests the deleteAction() method.
     *
     * @return void
     */
    public function testDeleteAction() {

        $client = $this->client;

        $client->request("GET", "/document/delete/10");
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index/1", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the downloadAction() method.
     *
     * @return void
     */
    public function testDownloadActionWithoutStorageProvider() {

        $client = $this->client;

        $client->request("GET", "/document/download/1");
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $this->assertEquals("Internal Server Error", $client->getResponse()->getContent());
    }

    /**
     * Tests the editAction() method.
     *
     * @return void
     */
    public function testEditAction() {

        $client = $this->client;

        $crawler = $client->request("GET", "/document/edit/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            "wbw_edm_document[name]" => "Home",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the indexAction() method.
     *
     * @return void
     */
    public function testIndexAction() {

        $client = $this->client;

        $client->request("GET", "/document/index");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));
    }

    /**
     * Tests the indexAction() method.
     *
     * @return void
     */
    public function testIndexActionWithParameters() {

        $parameters = TestFixtures::getPOSTData();

        $client = $this->client;

        $client->request("POST", "/document/index?id=1", $parameters, [], ["HTTP_X-Requested-With" => "XMLHttpRequest"]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get("Content-Type"));

        // Check the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(8, $res["data"]);

        //$this->assertArrayHasKey("DT_RowId", $res["data"][0]);
        //$this->assertArrayHasKey("DT_RowClass", $res["data"][0]);
        //$this->assertArrayHasKey("DT_RowData", $res["data"][0]);

        $this->assertContains("Applications", $res["data"][0]["name"]);
        $this->assertContains("Desktop", $res["data"][1]["name"]);
        $this->assertContains("Documents", $res["data"][2]["name"]);
        $this->assertContains("Downloads", $res["data"][3]["name"]);
        $this->assertContains("Music", $res["data"][4]["name"]);
        $this->assertContains("Pictures", $res["data"][5]["name"]);
        $this->assertContains("Public", $res["data"][6]["name"]);
        $this->assertContains("Templates", $res["data"][7]["name"]);
    }

    /**
     * Tests the moveAction() method.
     *
     * @return void
     */
    public function testMoveAction() {

        $client = $this->client;

        $crawler = $client->request("GET", "/document/move/9");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            "wbw_edm_document_move[parent]" => "8",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index/8", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the newAction() method.
     *
     * @return void
     */
    public function testNewAction() {

        $client = $this->client;

        $crawler = $client->request("GET", "/document/new");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            "wbw_edm_document[name]" => "GitHub",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the uploadAction() method.
     *
     * @return void
     */
    public function testUploadAction() {

        // Set an Uploaded file mock.
        $upload = new UploadedFile(getcwd() . "/Tests/Fixtures/Entity/TestDocument.php", "TestDocument.php", "application/php", 604);

        $client = $this->client;

        $crawler = $client->request("GET", "/document/upload");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            "wbw_edm_document_upload[uploadedFile]" => $upload,
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/document/index", $client->getResponse()->headers->get("location"));
    }
}
