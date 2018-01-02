<?php

/**
 * Disclaimer: This source code is protected by copyright law and by
 * international conventions.
 *
 * Any reproduction or partial or total distribution of the source code, by any
 * means whatsoever, is strictly forbidden.
 *
 * Anyone not complying with these provisions will be guilty of the offense of
 * infringement and the penal sanctions provided for by law.
 *
 * © 2017 All rights reserved.
 */

namespace WBW\Bundle\EDMBundle\Tests\Event;

use PHPUnit_Framework_TestCase;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;

/**
 * Document event test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Event
 * @final
 */
final class DocumentEventTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests __construct() method.
	 *
	 * @return void.
	 */
	public function testConstructor() {

		$arg = new Document();
		$obj = new DocumentEvent("name", $arg);

		$this->assertEquals($arg, $obj->getDocument());
		$this->assertEquals("name", $obj->getName());
	}

}
