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
	 * Before testDeleteAction(), create a new sub-directory.
	 *
	 * @return void.
	 */
	private function beforeDeleteAction() {

		$client = static::createClient();

		$crawler = $client->request("GET", "/edm/directory/new/1");
		$this->assertEquals("Creating a directory into /phpunit2", $crawler->filter("h3")->text());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([
			"edmbundle_directory[name]" => "unittest",
		]);
		$client->submit($form);
	}

	/**
	 * Tests the indexAction() method.
	 *
	 * @return void
	 */
	public function testIndexAction() {

		$client = static::createClient();

		$crawler = $client->request("GET", "/edm/directory/index");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals("Index of /", $crawler->filter("h3")->text());
	}

	/**
	 * Tests the newAction() method.
	 *
	 * @return void
	 * @depends testIndexAction
	 */
	public function testNewActionWithSuccess() {

		$client = static::createClient();

		$crawler = $client->request("GET", "/edm/directory/new");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals("Creating a directory into /", $crawler->filter("h3")->text());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([
			"edmbundle_directory[name]" => "phpunit",
		]);
		$client->submit($form);
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/edm/directory/index", $client->getResponse()->headers->get("location"));
	}

	/**
	 * Tests the newAction() method.
	 *
	 * @return void
	 */
	public function testNewActionWithNotBlankConstraint() {

		$client = static::createClient();

		$crawler = $client->request("GET", "/edm/directory/new");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([]);
		$client->submit($form);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains("The field Name is required", $client->getResponse()->getContent());
	}

	/**
	 * Tests the newAction() method.
	 *
	 * @return void
	 * @depends testNewActionWithSuccess
	 */
	public function testNewActionWithUniqueConstraint() {

		$client = static::createClient();

		$crawler = $client->request("GET", "/edm/directory/new");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([
			"edmbundle_directory[name]" => "phpunit",
		]);
		$client->submit($form);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertContains("This document already exists", $client->getResponse()->getContent());
	}

	/**
	 * Tests the editAction() method.
	 *
	 * @return void
	 * @depends testNewActionWithSuccess
	 */
	public function testEditActionWithSuccess() {

		$client = static::createClient();

		$crawler = $client->request("GET", "/edm/directory/edit/1");
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertEquals("Editing the directory /phpunit", $crawler->filter("h3")->text());

		$submit	 = $crawler->selectButton("Submit");
		$form	 = $submit->form([
			"edmbundle_directory[name]" => "phpunit2",
		]);
		$client->submit($form);
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/edm/directory/index", $client->getResponse()->headers->get("location"));
	}

	/**
	 * Tests the deleteAction() method.
	 *
	 * @return void
	 * @depends testNewActionWithSuccess
	 */
	public function testDeleteActionWithFail() {

		// Create a sub-directory.
		$this->beforeDeleteAction();

		$client = static::createClient();

		$client->request("GET", "/edm/directory/delete/1");
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/edm/directory/index", $client->getResponse()->headers->get("location"));

		$client->followRedirect();
		$this->assertContains("Directory deletion failed", $client->getResponse()->getContent());
	}

	/**
	 * Tests the deleteAction() method.
	 *
	 * @return void
	 * @depends testDeleteActionWithFail
	 */
	public function testDeleteActionWithSuccess() {

		$client = static::createClient();

		$client->request("GET", "/edm/directory/delete/2");
		$this->assertEquals(302, $client->getResponse()->getStatusCode());
		$this->assertEquals("/edm/directory/index/1", $client->getResponse()->headers->get("location"));

		$client->followRedirect();
		$this->assertContains("Directory deletion successful", $client->getResponse()->getContent());
	}

}
