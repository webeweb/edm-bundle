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
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractWebTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\TestFixtures;

/**
 * Dropzone controller test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Controller
 */
class DropzoneControllerTest extends AbstractWebTestCase {

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
     * Tests the indexAction() method.
     *
     * @return void
     */
    public function testIndexAction() {

        $client = $this->client;

        $client->request("GET", "/edm/dropzone/index/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get("Content-Type"));

        // Check the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(0, $res);
    }

    /**
     * Tests the showAction() method.
     *
     * @return void
     */
    public function testShowAction() {

        $client = $this->client;

        $client->request("GET", "/edm/dropzone/show/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get("Content-Type"));

        // Check the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(12, $res);

        $this->assertEquals(1, $res["id"]);
        $this->assertNotNull($res["createdAt"]);
        $this->assertNull($res["extension"]);
        $this->assertEquals("Home", $res["filename"]);
        $this->assertNull($res["hash"]);
        $this->assertNull($res["mimeType"]);
        $this->assertEquals("Home", $res["name"]);
        $this->assertEquals(0, $res["numberDownloads"]);
        $this->assertNull($res["parent"]);
        $this->assertEquals(0, $res["size"]);
        $this->assertEquals(DocumentInterface::TYPE_DIRECTORY, $res["type"]);
        $this->assertNull($res["updatedAt"]);
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

        $crawler = $client->request("GET", "/edm/dropzone/upload");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("text/html; charset=UTF-8", $client->getResponse()->headers->get("Content-Type"));

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            "wbw_edm_document_upload[uploadedFile]" => $upload,
        ]);
        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(200, $res["status"]);
        $this->assertEquals("Document uploaded successfully", $res["notify"]);
    }
}
