<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Entity;

/**
 * Document interface.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Entity
 */
interface DocumentInterface {

	/**
	 * Type "Directory".
	 *
	 * @var integer
	 */
	const TYPE_DIRECTORY = 117;

	/**
	 * Type "Document".
	 *
	 * @var integer
	 */
	const TYPE_DOCUMENT = 95;

}
