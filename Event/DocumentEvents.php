<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Event;

/**
 * Document events.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Event
 * @final
 */
final class DocumentEvents {

	/**
	 * Directory delete action.
	 *
	 * @string
	 */
	const DIRECTORY_DELETE = "webeweb.bundle.edmbundle.event.directory.delete";

	/**
	 * Directory edit action.
	 *
	 * @string
	 */
	const DIRECTORY_EDIT = "webeweb.bundle.edmbundle.event.directory.edit";

	/**
	 * Directory index action.
	 *
	 * @string
	 */
	const DIRECTORY_INDEX = "webeweb.bundle.edmbundle.event.directory.index";

	/**
	 * Directory move action.
	 *
	 * @string
	 */
	const DIRECTORY_MOVE = "webeweb.bundle.edmbundle.event.directory.move";

	/**
	 * Directory new action.
	 *
	 * @string
	 */
	const DIRECTORY_NEW = "webeweb.bundle.edmbundle.event.directory.new";

	/**
	 * Document delete action.
	 *
	 * @string
	 */
	const DOCUMENT_DELETE = "webeweb.bundle.edmbundle.event.document.delete";

	/**
	 * Document download action.
	 *
	 * @string
	 */
	const DOCUMENT_DOWNLOAD = "webeweb.bundle.edmbundle.event.document.download";

	/**
	 * Document edit action.
	 *
	 * @string
	 */
	const DOCUMENT_EDIT = "webeweb.bundle.edmbundle.event.document.edit";

	/**
	 * Document move action.
	 *
	 * @string
	 */
	const DOCUMENT_MOVE = "webeweb.bundle.edmbundle.event.document.move";

	/**
	 * Document upload action.
	 *
	 * @string
	 */
	const DOCUMENT_UPLOAD = "webeweb.bundle.edmbundle.event.document.upload";

	/**
	 * Constructor.
	 */
	protected function __construct() {
		// NOTHING TO TO.
	}

}
