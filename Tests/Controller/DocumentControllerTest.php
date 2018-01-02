<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Tests\FunctionalTest;

/**
 * Document controller test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Controller
 * @final
 */
final class DocumentControllerTest extends FunctionalTest {

	/**
	 * Tests the indexAction() method.
	 *
	 * @return void
	 */
	public function testIndexAction() {

		$client = static::createClient();

		$client->request("GET", "/directory/open");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	/**
	 * Tests the newAction() method.
	 *
	 * @return void
	 * @depends testIndexAction
	 */
	public function testNewAction() {

		$client = static::createClient();

		$crawler = $client->request("GET", "/directory/new");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals("Creating a directory into /", $crawler->filter("h3")->text());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([
			"edmbundle_new_document[name]" => "phpunit",
		]);
		$client->submit($form);
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/directory/open", $client->getResponse()->headers->get("location"));
	}

	/**
	 * Tests the newAction() method.
	 *
	 * @return void
	 * @depends testNewAction
	 */
	public function testUploadAction() {

		$upload = new UploadedFile(getcwd() . "/Tests/Fixtures/Entity/TestDocument.php", "TestDocument.php", "application/php", 604);

		$client = static::createClient();

		$crawler = $client->request("GET", "/directory/upload/1");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals("Uploading a document into /phpunit", $crawler->filter("h3")->text());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([
			"edmbundle_upload_document[name]"	 => "DocumentControllerTest",
			"edmbundle_upload_document[upload]"	 => $upload,
		]);
		$client->submit($form);
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/directory/open/1", $client->getResponse()->headers->get("location"));
	}

	/**
	 * Tests the editAction() method.
	 *
	 * @return void
	 * @depends testNewAction
	 */
	public function testEditActionWithDirectory() {

		$client = static::createClient();

		$crawler = $client->request("GET", "/directory/edit/1");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals("Editing the directory /phpunit", $crawler->filter("h3")->text());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([
			"edmbundle_new_document[name]" => "phpunit2",
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

		$crawler = $client->request("GET", "/document/edit/2");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals("Editing the document /phpunit2/DocumentControllerTest.php", $crawler->filter("h3")->text());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([
			"edmbundle_new_document[name]" => "DocumentControllerTest2",
		]);
		$client->submit($form);
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/directory/open/1", $client->getResponse()->headers->get("location"));
	}

	/**
	 * Tests the deleteAction() method.
	 *
	 * @return void
	 * @depends testUploadAction
	 */
	public function testDeleteActionWithDocument() {

		$client = static::createClient();

		$client->request("GET", "/document/delete/2");
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/directory/open/1", $client->getResponse()->headers->get("location"));

		$client->followRedirect();
		$this->assertContains("Document deletion successful", $client->getResponse()->getContent());
	}

	/**
	 * Tests the deleteAction() method.
	 *
	 * @return void
	 * @depends testNewAction
	 */
	public function testDeleteActionWithDirectory() {

		$client = static::createClient();

		$client->request("GET", "/directory/delete/1");
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/directory/open", $client->getResponse()->headers->get("location"));

		$client->followRedirect();
		$this->assertContains("Directory deletion successful", $client->getResponse()->getContent());
	}

}
