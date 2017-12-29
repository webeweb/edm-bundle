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

use DateTime;
use ReflectionClass;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\Utility\DirectoryUtility;
use WBW\Library\Core\Utility\FileUtility;
use ZipArchive;

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
		$this->edmDirectory = realpath($edmDirectory);
	}

	/**
	 * Get an absolute path.
	 *
	 * @param Document $document The document.
	 * @param boolean $rename Rename ?
	 * @return string Returns the absolute path.
	 */
	private function getAbsolutePath(Document $document = null, $rename = false) {
		return implode("/", [$this->edmDirectory, $this->getRelativePath($document, $rename)]);
	}

	/**
	 * Get a filename.
	 *
	 * @param Document $document The document.
	 * @return string Returns the filename.
	 */
	public function getFilename(Document $document) {
		if ($document->isDirectory()) {
			return $document->getName();
		}
		return implode(".", [$document->getName(), $document->getExtension()]);
	}

	/**
	 * Get a flat tree.
	 *
	 * @param Document $document The document.
	 * @return Document[] Returns the flat tree.
	 */
	private function getFlatTree(Document $document) {

		// Initialize the output.
		$output = [];

		// Handle each children.
		foreach ($document->getChildrens() as $current) {
			$output = array_merge($output, [$current], $this->getFlatTree($current));
		}

		// Return the output.
		return $output;
	}

	/**
	 * Get a relative path.
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
			array_unshift($path, $this->getFilename($current));
			$current = $current->getParent();
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
	 * Download a document.
	 *
	 * @param Document $document The document.
	 * @return Document Returns the document.
	 */
	public function downloadDocument(Document $document) {
		if ($document->isDocument()) {
			return $document;
		}
		return $this->zipDocument($document);
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
	 * Create a ZIP document.
	 *
	 * @param Document $document The document.
	 * @return Document Returns the ZIP document.
	 */
	private function newZIPDocument(Document $document) {

		// Initialize the id.
		$id = (new DateTime())->format("YmdHisu");

		// Initialize the document.
		$output = new Document();
		$output->setExtension("zip");
		$output->setMimeType("application/zip");
		$output->setName($document->getName() . "-" . $id);
		$output->setType(Document::TYPE_DOCUMENT);

		// Set the id.
		$setID = (new ReflectionClass($output))->getProperty("id");
		$setID->setAccessible(true);
		$setID->setValue($output, $id . ".download");

		// Return the document.
		return $output;
	}

	public function readDocument(Document $document) {
		return FileUtility::getContents($this->getAbsolutePath($document, false));
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

	/**
	 * Zip a document.
	 *
	 * @param Document $document The document.
	 * @return Document Returns the document.
	 */
	private function zipDocument(Document $document) {

		// Initialize the document.
		$output = $this->newZIPDocument($document);

		// Initialize the filenames.
		$src = $this->getVirtualPath($document);
		$dst = $this->getAbsolutePath($output);

		// Initialize the ZIP archive.
		$zip = new ZipArchive();
		if (true !== $zip->open($dst, ZipArchive::CREATE)) {
			return null;
		}

		// Handle each document.
		foreach ($this->getFlatTree($document) as $current) {

			// Initialize the ZIP path.
			$zipPath = preg_replace("/^" . str_replace("/", "\/", $src . "/") . "/", "", $this->getVirtualPath($current));

			// Check the document type.
			if (true === $current->isDirectory()) {
				$zip->addEmptyDir($zipPath);
			}
			if (true === $current->isDocument()) {
				$zip->addFromString($zipPath, $this->readDocument($current));
			}
		}

		// Close the ZIP archive.
		$zip->close();

		// Get the zip size.
		$output->setSize(FileUtility::getSize($dst));

		// Return the document.
		return $output;
	}

}
