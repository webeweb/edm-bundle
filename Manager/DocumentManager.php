<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Manager;

use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Library\Core\Utility\DirectoryUtility;

/**
 * Document manager.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 * @final
 */
final class DocumentManager {

	/**
	 * Service name.
	 *
	 * @var string
	 */
	const SERVICE_NAME = "webeweb.bundle.edmbundle.manager.document";

	/**
	 * EDM directory.
	 *
	 * @var string
	 */
	private $edmDirectory;

	/**
	 * Constructor.
	 *
	 * @param string $edmDirectory The EDM directory.
	 */
	public function __construct($edmDirectory) {
		$this->edmDirectory = $edmDirectory;
	}

	/**
	 * Get the path.
	 *
	 * @param Document $document The document.
	 * @param boolean $rename Rename ?
	 * @return string Returns the path.
	 */
	private function getPath(Document $document, $rename = false) {

		// Initialize the path.
		$path = [];

		// Save the document.
		$current = $document;

		// Handle each parent.
		do {
			array_unshift($path, $current === $document && $rename === true ? $current->getOldName() : $current->getName());
			$current = $current === $document && $rename === true ? $current->getOldParent() : $current->getParent();
		} while (!is_null($current));

		// Add the root directory.
		array_unshift($path, $this->edmDirectory);

		// Return the path.
		return implode("/", $path);
	}

	/**
	 * Make a directory.
	 *
	 * @param Document $directory The directory.
	 * @return boolean Returns true in case of success, false otherwise.
	 */
	public function mkdir(Document $directory) {
		$path = $this->getPath($directory, false);
		if (file_exists($path) === true) {
			return false;
		}
		return mkdir($path);
	}

	/**
	 * Rename a directory.
	 *
	 * @param Document $directory The directory.
	 * @return boolean Returns true in case of success, false otherwise.
	 */
	public function rename(Document $directory) {
		$oldPath = $this->getPath($directory, true);
		$newPath = $this->getPath($directory, false);
		if ($oldPath === $newPath) {
			return true;
		}
		if (file_exists($newPath) === true) {
			return false;
		}
		return rename($oldPath, $newPath);
	}

	/**
	 * Remove a directory.
	 *
	 * @param Document $directory The directory.
	 * @return boolean Returns true in case of success, false otherwise.
	 */
	public function rmdir(Document $directory) {
		$path	 = $this->getPath($directory, false);
		$empty	 = DirectoryUtility::isEmpty($path);
		if ($empty !== true) {
			return is_null($empty) ? true : false;
		}
		return rmdir($path);
	}

}
