<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider\Storage;

use DateTime;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\StreamedResponse;
use WBW\Bundle\CoreBundle\Model\Attribute\StringDirectoryTrait;
use WBW\Bundle\CoreBundle\Service\LoggerTrait;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use ZipArchive;

/**
 * Filesystem storage provider.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider\Storage
 */
class FilesystemStorageProvider implements StorageProviderInterface {

    use LoggerTrait;
    use StringDirectoryTrait;

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "wbw.edm.provider.storage.filesystem";

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger The logger.
     * @param string $directory The directory.
     */
    public function __construct(LoggerInterface $logger, $directory) {
        $logger->debug(sprintf("Filesystem storage provider use this directory \"%s\"", $directory));
        $this->setDirectory($directory);
        $this->setLogger($logger);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteDirectory(DocumentInterface $document) {

        DocumentHelper::isDirectory($document);

        $pathname = $this->getAbsolutePath($document, false);
        $context  = [
            "_provider" => get_class($this),
        ];

        $this->logInfo(sprintf("Filesystem storage provider tries to delete the directory \"%s\"", $pathname), $context);

        $filesystem = new Filesystem();
        $filesystem->remove($pathname);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteDocument(DocumentInterface $document) {

        DocumentHelper::isDocument($document);

        $pathname = $this->getAbsolutePath($document);
        $context  = [
            "_provider" => get_class($this),
        ];

        $this->logInfo(sprintf("Filesystem storage provider tries to delete the document \"%s\"", $pathname), $context);

        $filesystem = new Filesystem();
        $filesystem->remove($pathname);
    }

    /**
     * {@inheritdoc}
     */
    public function downloadDirectory(DocumentInterface $directory) {
        return $this->newStreamedResponse($directory);
    }

    /**
     * {@inheritdoc}
     */
    public function downloadDocument(DocumentInterface $document) {
        return $this->newStreamedResponse($document);
    }

    /**
     * Get an absolute path.
     *
     * @param DocumentInterface $document The document.
     * @param bool $rename Rename ?
     * @return string Returns the absolute path.
     */
    protected function getAbsolutePath(DocumentInterface $document = null, $rename = false) {

        if (null === $document) {
            return $this->getDirectory();
        }

        $path = [
            $this->getDirectory(),
        ];

        foreach (DocumentHelper::getPaths($document, $rename) as $current) {
            $path[] = $current->getId();
        }

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Log an info.
     *
     * @param string $message The message.
     * @param array $context The context.
     * @return FilesystemStorageProvider Returns this filesystem storage provider.
     */
    protected function logInfo($message, array $context = []) {
        $this->getLogger()->info($message, $context);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function moveDocument(DocumentInterface $document) {

        $src = $this->getAbsolutePath($document, true);
        $dst = $this->getAbsolutePath($document, false);

        $context = [
            "_provider" => get_class($this),
        ];

        $this->logInfo(sprintf("Filesystem storage provider tries to move \"%s\" into \"%s\"", $src, $dst), $context);

        $filesystem = new Filesystem();
        $filesystem->rename($src, $dst);
    }

    /**
     * {@inheritDoc}
     */
    public function newDirectory(DocumentInterface $directory) {

        DocumentHelper::isDirectory($directory);

        $pathname = $this->getAbsolutePath($directory, false);
        $context  = [
            "_provider" => get_class($this),
        ];

        $this->logInfo(sprintf("Filesystem storage provider tries to create the directory \"%s\"", $pathname), $context);

        $filesystem = new Filesystem();
        $filesystem->mkdir($pathname);
    }

    /**
     * Create a new streamed response.
     *
     * @param DocumentInterface $document The document.
     * @return StreamedResponse Returns the streamed response.
     */
    protected function newStreamedResponse(DocumentInterface $document) {

        $myself = $this;

        /** @var callable $callback */
        $callback = function() use ($document, $myself) {

            if ($document->isDocument()) {
                $myself->streamDocument($document);
                return;
            }

            $archive = $myself->zipDirectory($document);
            $myself->streamDocument($archive);
        };

        $filename  = DocumentHelper::getFilename($document);
        $extension = $document->isDocument() ? "" : ".zip";
        $mimeType  = $document->isDocument() ? $document->getMimeType() : "application/zip";

        $response = new StreamedResponse();
        $response->headers->set("Content-Disposition", "attachement; filename=\"${filename}${extension}\"");
        $response->headers->set("Content-Type", $mimeType);
        $response->setCallback($callback);
        $response->setStatusCode(200);

        return $response;
    }

    /**
     * Create a ZIP document.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface Returns the ZIP document.
     * @throws ReflectionException Throws a Reflection exception if an error occurs.
     */
    protected function newZipDocument(DocumentInterface $document) {

        $id = (new DateTime())->format("YmdHisu");

        $model = new Document();
        $model->setExtension("zip");
        $model->setMimeType("application/zip");
        $model->setName($document->getName() . "-" . $id);
        $model->setType(DocumentInterface::TYPE_DOCUMENT);

        // Use reflection to set the private id attribute.
        $idProperty = (new ReflectionClass($model))->getProperty("id");
        $idProperty->setAccessible(true);
        $idProperty->setValue($model, $id . ".download");

        return $model;
    }

    /**
     * Set the directory.
     *
     * @param string $directory The directory.
     * @return StorageProviderInterface Returns this storage provider.
     */
    protected function setDirectory($directory) {
        $this->directory = $directory;
        return $this;
    }

    /**
     * Stream a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     */
    protected function streamDocument(DocumentInterface $document) {

        $filename = DocumentHelper::getPathname($document);

        $input  = fopen($filename);
        $output = fopen("php://output", "w+");

        while (false === feof($input)) {
            stream_copy_to_stream($input, $output, 4096);
        }

        fclose($input);
        fclose($output);

        if (true === $document->isDirectory()) {
            unlink($filename);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function uploadDocument(DocumentInterface $document) {

        DocumentHelper::isDocument($document);

        $src = $document->getUploadedFile()->getRealPath();
        $dst = $this->getAbsolutePath($document);

        $context = [
            "_provider" => get_class($this),
        ];

        $this->logInfo(sprintf("Filesystem storage provider tries to copy the uploaded document \"%s\" into \"%s\"", $src, $dst), $context);

        $filesystem = new Filesystem();
        $filesystem->copy($src, $dst);
    }

    /**
     * Zip a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return DocumentInterface Returns the zipped directory in case success.
     * @throws ReflectionException Throws a Reflection exception if an error occurs.
     */
    protected function zipDirectory(DocumentInterface $directory) {

        $archive = $this->newZipDocument($directory);

        $src = DocumentHelper::getPathname($directory);
        $dst = $this->getAbsolutePath($archive);

        $zip = new ZipArchive();
        $zip->open($dst, ZipArchive::CREATE);

        foreach (DocumentHelper::normalize($directory) as $current) {

            $pattern = implode("", ["/^", preg_quote("${src}/"), "/"]);
            $zipPath = preg_replace($pattern, DocumentHelper::getPathname($current));

            if (true === $current->isDirectory()) {
                $zip->addEmptyDir($zipPath);
            }
            if (true === $current->isDocument()) {
                $zip->addFromString($zipPath, file_get_contents($this->getAbsolutePath($current, false)));
            }
        }

        $zip->close();

        return $archive;
    }
}
