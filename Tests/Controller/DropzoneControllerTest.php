<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Bundle\EDMBundle\Tests\AbstractWebTestCase;

/**
 * Dropzone controller test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Controller
 * @final
 */
final class DropzoneControllerTest extends AbstractWebTestCase {

    /**
     * Tests the uploadAction() method.
     *
     * @return void
     */
    public function testUploadAction() {

        $upload = new UploadedFile(getcwd() . "/Tests/Fixtures/Entity/TestDocument.php", "TestDocument.php", "application/php", 604);

        $client = static::createClient();

        $crawler = $client->request("GET", "/dropzone/upload");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Uploading a document into /", $crawler->filter("h3")->text());

        $submit = $crawler->filter("form");
        $form   = $submit->form([
            "edmbundle_upload_document[upload]" => $upload,
        ]);
        $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the indexAction() method.
     *
     * @return void
     * @depends testUploadAction
     */
    public function testIndexAction() {

        $client = static::createClient();

        $client->request("GET", "/dropzone/index");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get("Content-Type"));

        // CHeck the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(1, $res);

        $this->assertEquals(1, $res[0]["id"]);
        $this->assertRegExp("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}\ [0-9]{2}:[0-9]{2}:[0-9]{2}\.[0-9]{6}/", $res[0]["createdAt"]["date"]);
        $this->assertEquals("php", $res[0]["extension"]);
        $this->assertEquals("TestDocument.php", $res[0]["filename"]);
        $this->assertEquals("application/octet-stream", $res[0]["mimeType"]);
        $this->assertEquals("TestDocument", $res[0]["name"]);
        $this->assertEquals(0, $res[0]["numberDownloads"]);
        $this->assertEquals(653, $res[0]["size"]);
        $this->assertEquals(DocumentInterface::TYPE_DOCUMENT, $res[0]["type"]);
        $this->assertNull($res[0]["updatedAt"]);
    }

}
