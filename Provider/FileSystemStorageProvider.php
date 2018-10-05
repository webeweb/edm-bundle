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
use Psr\Log\LoggerInterface;
use ReflectionClass;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\IO\DirectoryHelper;
use WBW\Library\Core\IO\FileHelper;
use ZipArchive;

/**
 * File system storage provider.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider
 */
class FileSystemStorageProvider implements StorageProviderInterface {

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "webeweb.edm.provider.storage.filesystem";

    /**
     * Directory.
     *
     * @var string
     */
    private $directory;

    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param string $directory The directory.
     */
    public function __construct($directory) {
        $this->setDirectory($directory);
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
        $src = DocumentHelper::getPathname($directory);
        $dst = $this->getAbsolutePath($archive);

        // Initialize the ZIP archive.
        $zip = new ZipArchive();
        if (true !== $zip->open($dst, ZipArchive::CREATE)) {
            return null;
        }

        // Handle each document.
        foreach (DocumentHelper::toArray($directory) as $current) {

            // Initialize the ZIP path.
            $zipPath = preg_replace("/^" . str_replace("/", "\/", $src . "/") . "/", "", DocumentHelper::getPathname($current));

            // Check the document type.
            if (true === $current->isDirectory()) {
                $zip->addEmptyDir($zipPath);
            }
            if (true === $current->isDocument()) {
                $zip->addFromString($zipPath, FileHelper::getContents($this->getAbsolutePath($current, false)));
            }
        }

        // Close the ZIP archive.
        $zip->close();

        // Get the ZIP size.
        $archive->setSize(FileHelper::getSize($dst));

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
     * @param bool $rename Rename ?
     * @return string Returns the absolute path.
     */
    private function getAbsolutePath(DocumentInterface $document = null, $rename = false) {

        // Check the document.
        if (null === $document) {
            return $this->getDirectory();
        }

        // Initialize the path.
        $path = [];

        // Add the directory.
        $path[] = $this->getDirectory();

        // Handle each document.
        foreach (DocumentHelper::getPaths($document, $rename) as $current) {
            $path[] = $current->getId();
        }

        // Return the path.
        return implode("/", $path);
    }

    /**
     * Get the directory.
     *
     * @return string Returns the directory.
     */
    public function getDirectory() {
        return $this->directory;
    }

    /**
     * Get the logger.
     *
     * @return LoggerInterface Returns the logger.
     */
    public function getLogger() {
        return $this->logger;
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
        DirectoryHelper::delete($this->getAbsolutePath($document, false));
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
        FileHelper::delete($this->getAbsolutePath($document, false));
    }

    /**
     * {@inheritdoc}
     */
    public function onMovedDocument(DocumentInterface $document) {

        // Move the document.
        FileHelper::rename($this->getAbsolutePath($document, true), $this->getAbsolutePath($document, false));
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
        DirectoryHelper::create($this->getAbsolutePath($document, false));
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
        copy($document->getUpload()->getPathname(), $this->getAbsolutePath($document));
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
        return FileHelper::getContents($this->getAbsolutePath($document, false));
    }

    /**
     * Set the directory.
     *
     * @param string $directory The directory.
     * @return StorageProviderInterface Returns this storage provider.
     */
    protected function setDirectory($directory) {
        $this->directory = realpath($directory);
        return $this;
    }

    /**
     * Set the logger.
     *
     * @param LoggerInterface $logger The logger.
     * @return StorageProviderInterface Returns this storage provider.
     */
    protected function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
        return $this;
    }

}
