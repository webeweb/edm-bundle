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
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractWebTestCase;

/**
 * Dropzone controller test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Controller
 */
class DropzoneControllerTest extends AbstractWebTestCase {

    /**
     * Tests indexAction()
     *
     * @return void
     */
    public function testIndexAction(): void {

        $client = $this->client;

        $client->request("GET", "/dropzone/index/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get("Content-Type"));

        // Check the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(0, $res);
    }

    /**
     * Tests serializeAction()
     *
     * @return void
     */
    public function testSerializeAction(): void {

        $client = $this->client;

        $client->request("GET", "/dropzone/serialize/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get("Content-Type"));

        // Check the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(16, $res);

        $this->assertEquals(1, $res["id"]);
        $this->assertNotNull($res["createdAt"]);
        $this->assertNull($res["extension"]);
        $this->assertEquals("Home", $res["filename"]);
        $this->assertNull($res["hashMd5"]);
        $this->assertNull($res["hashSha1"]);
        $this->assertNull($res["hashSha256"]);
        $this->assertNull($res["mimeType"]);
        $this->assertEquals("Home", $res["name"]);
        $this->assertEquals(0, $res["numberDownloads"]);
        $this->assertNull($res["parent"]);
        $this->assertEquals(0, $res["size"]);
        $this->assertEquals(DocumentInterface::TYPE_DIRECTORY, $res["type"]);
        $this->assertNull($res["updatedAt"]);
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

        $crawler = $client->request("GET", "/dropzone/upload");
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
