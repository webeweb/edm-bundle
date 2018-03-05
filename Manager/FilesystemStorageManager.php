<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Manager;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use ReflectionClass;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Utility\DocumentUtility;
use WBW\Library\Core\Exception\Argument\IllegalArgumentException;
use WBW\Library\Core\Utility\DirectoryUtility;
use WBW\Library\Core\Utility\FileUtility;
use ZipArchive;

/**
 * Filesystem storage manager.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Manager
 * @final
 */
final class FilesystemStorageManager {

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "webeweb.bundle.edmbundle.manager.storage.filesystem";

    /**
     * Directory.
     *
     * @var string
     */
    private $directory;

    /**
     * Entity manager.
     *
     * @var ObjectManager
     */
    private $em;

    /**
     * Constructor.
     *
     * @param ObjectManager $em The entity manager.
     * @param string $directory The directory.
     */
    public function __construct(ObjectManager $em, $directory) {
        $this->directory = realpath($directory);
        $this->em        = $em;
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
     * Download a document.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface Returns the document.
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
     * On deleted directory.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a directory.
     */
    public function onDeletedDirectory(DocumentEvent $event) {

        // Check the document type.
        if (false === $event->getDocument()->isDirectory()) {
            throw new IllegalArgumentException("The document must be a directory");
        }

        // Delete the directory.
        DirectoryUtility::delete($this->getAbsolutePath($event->getDocument(), false));
    }

    /**
     * On deleted document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
     */
    public function onDeletedDocument(DocumentEvent $event) {

        // Check the document type.
        if (false === $event->getDocument()->isDocument()) {
            throw new IllegalArgumentException("The document must be a document");
        }

        // Delete the document.
        FileUtility::delete($this->getAbsolutePath($event->getDocument(), false));
    }

    /**
     * On downloaded document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     */
    public function onDownloadedDocument(DocumentEvent $event) {

        // Get the document.
        $document = $event->getDocument();

        // Increment the number of downloads.
        $document->incrementNumberDownloads();

        // Update the entities.
        $this->em->persist($document);
        $this->em->flush();
    }

    /**
     * On moved document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     */
    public function onMovedDocument(DocumentEvent $event) {

        // Get the document.
        $document = $event->getDocument();

        // Decrease the size.
        if (null !== $document->getParentBackedUp()) {
            foreach (DocumentUtility::getPaths($document->getParentBackedUp()) as $current) {
                $current->decreaseSize($document->getSize());
                $this->em->persist($current);
            }
        }

        // Increase the size.
        if (null !== $document->getParent()) {
            foreach (DocumentUtility::getPaths($document->getParent()) as $current) {
                $current->increaseSize($document->getSize());
                $this->em->persist($current);
            }
        }

        // Update the entities.
        $this->em->persist($document);
        $this->em->flush();

        // Move the document.
        FileUtility::rename($this->getAbsolutePath($document, true), $this->getAbsolutePath($document, false));
    }

    /**
     * On new directory.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a directory.
     */
    public function onNewDirectory(DocumentEvent $event) {

        // Check the document type.
        if (false === $event->getDocument()->isDirectory()) {
            throw new IllegalArgumentException("The document must be a directory");
        }

        // Create the directory.
        DirectoryUtility::create($this->getAbsolutePath($event->getDocument(), false));
    }

    /**
     * On uploaded document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
     */
    public function onUploadedDocument(DocumentEvent $event) {

        // Check the document type.
        if (false === $event->getDocument()->isDocument()) {
            throw new IllegalArgumentException("The document must be a document");
        }

        // Get the document.
        $document = $event->getDocument();

        // Check the document upload.
        if (null !== $document->getUpload()) {
            $document->setExtension($document->getUpload()->getClientOriginalExtension());
            $document->setMimeType($document->getUpload()->getClientMimeType());
            $document->setSize(FileUtility::getSize($document->getUpload()->getPathname()));
        }

        // Increase the size.
        if (null !== $document->getParent()) {
            foreach (DocumentUtility::getPaths($document->getParent()) as $current) {
                $current->increaseSize($document->getSize());
                $this->em->persist($current);
            }
        }

        // Update the entities.
        $this->em->persist($document);
        $this->em->flush();

        // Save the document.
        $document->getUpload()->move($this->getAbsolutePath($document->getParent()), $document->getId());
    }

    /**
     * Read a document.
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the document content.
     * @throws IllegalArgumentException Throws an illegal argument exception if the document is not a document.
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
