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
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\Utility\DirectoryUtility;
use WBW\Library\Core\Utility\FileUtility;

/**
 * Storage manager.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 * @final
 */
final class StorageManager {

	/**
	 * Service name.
	 *
	 * @var string
	 */
	const SERVICE_NAME = "webeweb.bundle.edmbundle.manager.storage";

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
	private function getAbsolutePath(Document $document = null, $rename = false) {
		return implode("/", [$this->edmDirectory, $this->getRelativePath($document, $rename)]);
	}

	/**
	 * Get the relative path.
	 *
	 * @param Document $document The document.
	 * @param boolean $rename Rename ?
	 * @return string Returns the relative path.
	 */
	public function getRelativePath(Document $document = null, $rename = false) {

		// Initialize the path.
		$path = [];

		// Save the document.
		$current = $document;

		// Handle each parent.
		while (null !== $current) {
			array_unshift($path, $current->getId());
			$current = $current === $document && true === $rename ? $current->getParentBackedUp() : $current->getParent();
		}

		// Return the path.
		return implode("/", $path);
	}

	/**
	 * Get a virtual path.
	 *
	 * @param Document $document The document.
	 * @return string Returns the virtual path.
	 */
	public function getVirtualPath(Document $document = null) {

		// Initialize the path.
		$path = [];

		// Save the document.
		$current = $document;

		// Handle each parent.
		while (null !== $current) {
			array_unshift($path, $current->getName());
			$current = $current->getParent();
		}

		// Check the extension.
		if (null !== $document && null !== $document->getExtension()) {
			$path[count($path) - 1] .= "." . $document->getExtension();
		}


		// Return the path.
		return implode("/", $path);
	}

	/**
	 * Delete a document.
	 *
	 * @param Document $document The document.
	 * @return boolean Returns true in case of success, false otherwise.
	 */
	public function deleteDocument(Document $document) {
		if (true === $document->isDirectory()) {
			return DirectoryUtility::delete($this->getAbsolutePath($document, false));
		}
		return FileUtility::delete($this->getAbsolutePath($document, false));
	}

	/**
	 * Move a document.
	 *
	 * @param Document $document The document.
	 * @return boolean Returns true in case of success, false otherwise.
	 * @throws IllegalArgumentException Throws an illegal argument exception if the document is a directory.
	 */
	public function moveDocument(Document $document) {
		return FileUtility::rename($this->getAbsolutePath($document, true), $this->getAbsolutePath($document, false));
	}

	/**
	 * Save a document.
	 *
	 * @param Document $document The directory.
	 * @return boolean Returns true in case of success, false otherwise.
	 * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
	 */
	public function saveDocument(Document $document) {
		if (true === $document->isDirectory()) {
			return DirectoryUtility::create($this->getAbsolutePath($document, false));
		}
		return $document->getUpload()->move($this->getAbsolutePath($document->getParent()), $document->getId());
	}

}
