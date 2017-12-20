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
 * Directory controller test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Controller
 * @final
 */
final class DirectoryControllerTest extends FunctionalTest {

	/**
	 * Tests the indexAction() method.
	 *
	 * @return void
	 */
	public function testIndexAction() {

		$client	 = static::createClient();
		$crawler = $client->request("GET", "/directory/index");
	}

}
