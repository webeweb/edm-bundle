<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider;

use DateTime;
use ReflectionClass;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Bundle\EDMBundle\Utility\DocumentUtility;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\Utility\IO\DirectoryUtility;
use WBW\Library\Core\Utility\IO\FileUtility;
use ZipArchive;

/**
 * File system storage provider.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider
 * @final
 */
final class FileSystemStorageProvider implements StorageProviderInterface {

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "webeweb.bundle.edmbundle.provider.storage.filesystem";

    /**
     * Directory.
     *
     * @var string
     */
    private $directory;

    /**
     * Constructor.
     *
     * @param string $directory The directory.
     */
    public function __construct($directory) {
        $this->directory = realpath($directory);
    }

    /**
     * Compress a directory.
     *
     * @param DocumentInterface $directory The document.
     * @return DocumentInterface Returns the document.
     */
    private function compressDirectory(DocumentInterface $directory) {

        // Initialize the document.
        $archive = $this->newZIPDocument($directory);

        // Initialize the filenames.
        $src = DocumentUtility::getPathname($directory);
        $dst = $this->getAbsolutePath($archive);

        // Initialize the ZIP archive.
        $zip = new ZipArchive();
        if (true !== $zip->open($dst, ZipArchive::CREATE)) {
            return null;
        }

        // Handle each document.
        foreach ($this->getFlatTree($directory) as $current) {

            // Initialize the ZIP path.
            $zipPath = preg_replace("/^" . str_replace("/", "\/", $src . "/") . "/", "", DocumentUtility::getPathname($current));

            // Check the document type.
            if (true === $current->isDirectory()) {
                $zip->addEmptyDir($zipPath);
            }
            if (true === $current->isDocument()) {
                $zip->addFromString($zipPath, FileUtility::getContents($this->getAbsolutePath($current, false)));
            }
        }

        // Close the ZIP archive.
        $zip->close();

        // Get the ZIP size.
        $archive->setSize(FileUtility::getSize($dst));

        // Return the document.
        return $archive;
    }

    /**
     * {@inheritdoc}
     */
    public function downloadDocument(DocumentInterface $document) {
        if ($document->isDocument()) {
            return $document;
        }
        return $this->compressDirectory($document);
    }

    /**
     * Get an absolute path.
     *
     * @param DocumentInterface $document The document.
     * @param boolean $rename Rename ?
     * @return string Returns the absolute path.
     */
    private function getAbsolutePath(DocumentInterface $document = null, $rename = false) {

        // Check the document.
        if (null === $document) {
            return $this->directory;
        }

        // Initialize the path.
        $path = [];

        // Add the directory.
        $path[] = $this->directory;

        // Handle each document.
        foreach (DocumentUtility::getPaths($document, $rename) as $current) {
            $path[] = $current->getId();
        }

        // Return the path.
        return implode("/", $path);
    }

    /**
     * Get a flat tree.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface[] Returns the flat tree.
     */
    private function getFlatTree(DocumentInterface $document) {

        // Initialize the tree.
        $tree = [];

        // Handle each children.
        foreach ($document->getChildrens() as $current) {
            $tree = array_merge($tree, [$current], $this->getFlatTree($current));
        }

        // Return the tree.
        return $tree;
    }

    /**
     * Create a ZIP document.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface Returns the ZIP document.
     */
    private function newZIPDocument(DocumentInterface $document) {

        // Initialize the id.
        $id = (new DateTime())->format("YmdHisu");

        // Initialize the document.
        $entity = new Document();
        $entity->setExtension("zip");
        $entity->setMimeType("application/zip");
        $entity->setName($document->getName() . "-" . $id);
        $entity->setType(DocumentInterface::TYPE_DOCUMENT);

        // Set the id.
        $setID = (new ReflectionClass($entity))->getProperty("id");
        $setID->setAccessible(true);
        $setID->setValue($entity, $id . ".download");

        // Return the document.
        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function onDeletedDirectory(DocumentInterface $document) {

        // Check the document type.
        if (false === $document->isDirectory()) {
            throw new IllegalArgumentException("The document must be a directory");
        }

        // Delete the directory.
        DirectoryUtility::delete($this->getAbsolutePath($document, false));
    }

    /**
     * {@inheritdoc}
     */
    public function onDeletedDocument(DocumentInterface $document) {

        // Check the document type.
        if (false === $document->isDocument()) {
            throw new IllegalArgumentException("The document must be a document");
        }

        // Delete the document.
        FileUtility::delete($this->getAbsolutePath($document, false));
    }

    /**
     * {@inheritdoc}
     */
    public function onMovedDocument(DocumentInterface $document) {

        // Move the document.
        FileUtility::rename($this->getAbsolutePath($document, true), $this->getAbsolutePath($document, false));
    }

    /**
     * {@inheritdoc}
     */
    public function onNewDirectory(DocumentInterface $document) {

        // Check the document type.
        if (false === $document->isDirectory()) {
            throw new IllegalArgumentException("The document must be a directory");
        }

        // Create the directory.
        DirectoryUtility::create($this->getAbsolutePath($document, false));
    }

    /**
     * {@inheritdoc}
     */
    public function onUploadedDocument(DocumentInterface $document) {

        // Check the document type.
        if (false === $document->isDocument()) {
            throw new IllegalArgumentException("The document must be a document");
        }

        // Save the document.
        $document->getUpload()->move($this->getAbsolutePath($document->getParent()), $document->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function readDocument(DocumentInterface $document) {

        // Check the document type.
        if (false === $document->isDocument()) {
            throw new IllegalArgumentException("The document must be a document");
        }

        // Returns the content.
        return FileUtility::getContents($this->getAbsolutePath($document, false));
    }

}
