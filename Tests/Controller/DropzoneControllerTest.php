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

        $client->request("GET", "/dropzone/index/1");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("application/json", $client->getResponse()->headers->get("Content-Type"));

        // Check the JSON response.
        $res = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(0, $res);
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
