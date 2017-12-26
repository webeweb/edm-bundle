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
	 * Get the absolute path.
	 *
	 * @param Document $document The document.
	 * @param boolean $rename Rename ?
	 * @return string Returns the absolute path.
	 */
	private function getAbsolutePath(Document $document, $rename = false) {
		return implode("/", [$this->edmDirectory, $this->getRelativePath($document, $rename)]);
	}

	/**
	 * Get the relative path.
	 *
	 * @param Document $document The document.
	 * @param boolean $rename Rename ?
	 * @return string Returns the relative path.
	 */
	public function getRelativePath(Document $document, $rename = false) {

		// Initialize the path.
		$path = [];

		// Save the document.
		$current = $document;

		// Handle each parent.
		do {
			array_unshift($path, $current === $document && $rename === true ? $current->getOldName() : $current->getName());
			$current = $current === $document && $rename === true ? $current->getOldParent() : $current->getParent();
		} while (!is_null($current));

		// Return the path.
		return implode("/", $path);
	}

	/**
	 * Make a directory.
	 *
	 * @param Document $directory The directory.
	 * @return boolean Returns true in case of success, false otherwise.
	 */
	public function makeDirectory(Document $directory) {
		return DirectoryUtility::create($this->getAbsolutePath($directory, false));
	}

	/**
	 * Remove a directory.
	 *
	 * @param Document $directory The directory.
	 * @return boolean Returns true in case of success, false otherwise.
	 */
	public function removeDirectory(Document $directory) {
		return DirectoryUtility::delete($this->getAbsolutePath($directory, false));
	}

	/**
	 * Rename a directory.
	 *
	 * @param Document $directory The directory.
	 * @return boolean Returns true in case of success, false otherwise.
	 */
	public function renameDirectory(Document $directory) {
		return DirectoryUtility::rename($this->getAbsolutePath($directory, true), $this->getAbsolutePath($directory, false));
	}

}
