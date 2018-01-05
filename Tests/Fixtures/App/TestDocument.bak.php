<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Fixtures\App;

use WBW\Bundle\EDMBundle\Entity\Document;

/**
 * Test document entity.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Fixtures\App
 * @final
 */
final class TestDocument extends Document {

	/**
	 * Set the id.
	 *
	 * @param integer $id The id.
	 */
	public function setId($id) {
		$this->id = $id;
	}

}
