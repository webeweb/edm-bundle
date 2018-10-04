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
use WBW\Bundle\EDMBundle\Tests\Cases\AbstractEDMWebTestCase;

/**
 * Document controller test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Controller
 * @final
 */
final class DocumentControllerTest extends AbstractEDMWebTestCase {

    /**
     * Tests the openAction() method.
     *
     * @return void
     */
    public function testOpenAction() {

        $client = static::createClient();

        $client->request("GET", "/directory/open");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the newAction() method.
     *
     * @return void
     */
    public function testNewAction() {

        for ($i = 1; $i < 3; ++$i) {

            $client = static::createClient();

            $crawler = $client->request("GET", "/directory/new");
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $this->assertEquals("Creating a directory into /", $crawler->filter("h3")->text());

            $submit = $crawler->selectButton("Submit");
            $form   = $submit->form([
                "edmbundle_new_document[name]" => "Directory " . $i,
            ]);
            $client->submit($form);
            $this->assertEquals(302, $client->getResponse()->getStatusCode());
            $this->assertEquals("/directory/open", $client->getResponse()->headers->get("location"));
        }
    }

    /**
     * Tests the newAction() method.
     *
     * @return void
     */
    public function testUploadAction() {

        $upload = new UploadedFile(getcwd() . "/Tests/Fixtures/Entity/TestDocument.php", "TestDocument.php", "application/php", 604);

        $client = static::createClient();

        $crawler = $client->request("GET", "/directory/upload");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Uploading a document into /", $crawler->filter("h3")->text());

        $submit = $crawler->selectButton("Submit");
        $form   = $submit->form([
            "edmbundle_upload_document[upload]" => $upload,
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/directory/open", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the editAction() method.
     *
     * @return void
     * @depends testNewAction
     */
    public function testEditActionWithDirectory() {

        $client = static::createClient();

        $crawler = $client->request("GET", "/directory/edit/2");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Editing the directory /Directory 2", $crawler->filter("h3")->text());

        $submit = $crawler->selectButton("Submit");
        $form   = $submit->form([
            "edmbundle_new_document[name]" => "Subdirectory 1",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/directory/open", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the editAction() method.
     *
     * @return void
     * @depends testUploadAction
     */
    public function testEditActionWithDocument() {

        $client = static::createClient();

        $crawler = $client->request("GET", "/document/edit/3");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Editing the document /TestDocument.php", $crawler->filter("h3")->text());

        $submit = $crawler->selectButton("Submit");
        $form   = $submit->form([
            "edmbundle_new_document[name]" => "Document 1",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/directory/open", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the moveAction() method.
     *
     * @return void
     * @depends testNewAction
     */
    public function testMoveActionWithDirectory() {

        $client = static::createClient();

        $crawler = $client->request("GET", "/directory/move/2");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Moving the directory /Subdirectory 1", $crawler->filter("h3")->text());

        $submit = $crawler->selectButton("Submit");
        $form   = $submit->form([
            "edmbundle_move_document[parent]" => "1",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/directory/open/1", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the moveAction() method.
     *
     * @return void
     * @depends testUploadAction
     */
    public function testMoveActionWithDocument() {

        $client = static::createClient();

        $crawler = $client->request("GET", "/document/move/3");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("Moving the document /Document 1.php", $crawler->filter("h3")->text());

        $submit = $crawler->selectButton("Submit");
        $form   = $submit->form([
            "edmbundle_move_document[parent]" => "2",
        ]);
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/directory/open/2", $client->getResponse()->headers->get("location"));
    }

    /**
     * Tests the downloadAction() method.
     *
     * @return void
     * @depends testUploadAction
     */
    public function testDownloadAction() {

        $client = static::createClient();

        $client->request("GET", "/directory/download/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("private", $client->getResponse()->headers->get("Cache-Control"));
        $this->assertEquals("application/zip", $client->getResponse()->headers->get("Content-Type"));
        $this->assertContains("attachment; filename=\"Directory 1-", $client->getResponse()->headers->get("Content-Disposition"));
        $this->assertGreaterThan(0, $client->getResponse()->headers->get("Content-Length"));
    }

    /**
     * Tests the deleteAction() method.
     *
     * @return void
     * @depends testNewAction
     */
    public function testDeleteActionWithForeignKeyConstraintViolation() {

        $client = static::createClient();

        $client->request("GET", "/directory/delete/1");
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/directory/open", $client->getResponse()->headers->get("location"));

        $client->followRedirect();
        $this->assertContains("Directory deletion failed : the directory is not empty", $client->getResponse()->getContent());
    }

    /**
     * Tests the deleteAction() method.
     *
     * @return void
     * @depends testMoveActionWithDocument
     */
    public function testDeleteActionWithDocument() {

        $client = static::createClient();

        $client->request("GET", "/document/delete/3");
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals("/directory/open/2", $client->getResponse()->headers->get("location"));

        $client->followRedirect();
        $this->assertContains("Document deletion successful", $client->getResponse()->getContent());
    }

    /**
     * Tests the deleteAction() method.
     *
     * @return void
     * @depends testDeleteActionWithDocument
     */
    public function testDeleteActionWithDirectory() {

        for ($i = 2; 0 < $i; --$i) {

            $client = static::createClient();

            $client->request("GET", "/directory/delete/" . $i);
            $this->assertEquals(302, $client->getResponse()->getStatusCode());
            $this->assertContains("/directory/open", $client->getResponse()->headers->get("location"));

            $client->followRedirect();
            $this->assertContains("Directory deletion successful", $client->getResponse()->getContent());
        }
    }

}
