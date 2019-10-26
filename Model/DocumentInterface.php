<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Model;

use DateTime;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document interface.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Model
 */
interface DocumentInterface extends JsonSerializable {

    /**
     * Type "directory.
     *
     * @var int
     */
    const TYPE_DIRECTORY = 705;

    /**
     * Type "document.
     *
     * @var int
     */
    const TYPE_DOCUMENT = 485;

    /**
     * Add a child.
     *
     * @param DocumentInterface $child The child.
     * @return DocumentInterface Returns this document.
     */
    public function addChild(DocumentInterface $child);

    /**
     * Decrease the size.
     *
     * @param float $size The size.
     * @return DocumentInterface Returns this document.
     */
    public function decreaseSize($size);

    /**
     * Get the children.
     *
     * @return Collection Returns the children.
     */
    public function getChildren();

    /**
     * Get the created at.
     *
     * @return DateTime Returns the created at.
     */
    public function getCreatedAt();

    /**
     * Get the extension.
     *
     * @return string Returns the extension.
     */
    public function getExtension();

    /**
     * Get the hash.
     *
     * @return string Returns the hash.
     */
    public function getHash();

    /**
     * Get the id.
     *
     * @return int Returns the id.
     */
    public function getId();

    /**
     * Get the mime type.
     *
     * @return string Returns the mime type.
     */
    public function getMimeType();

    /**
     * Get the name.
     *
     * @return string Returns the name.
     */
    public function getName();

    /**
     * Get the number of downloads.
     *
     * @return int Returns the number of downloads.
     */
    public function getNumberDownloads();

    /**
     * Get the parent.
     *
     * @return DocumentInterface Returns the parent.
     */
    public function getParent();

    /**
     * Get the saved parent.
     *
     * @return DocumentInterface Returns the saved parent.
     */
    public function getSavedParent();

    /**
     * Get the size.
     *
     * @return float Returns the size.
     */
    public function getSize();

    /**
     * Get the type.
     *
     * @return int Returns the type.
     */
    public function getType();

    /**
     * Get the updated at.
     *
     * @return DateTime Returns the updated at.
     */
    public function getUpdatedAt();

    /**
     * Get the uploaded file.
     *
     * @return UploadedFile Returns the uploaded file.
     */
    public function getUploadedFile();

    /**
     * Determines if this document has children.
     *
     * @return bool Returns true in case of success, false otherwise.
     */
    public function hasChildren();

    /**
     * Increase the size.
     *
     * @param float $size The size.
     * @return DocumentInterface Returns this document.
     */
    public function increaseSize($size);

    /**
     * Increments the number of downloads.
     *
     * @return DocumentInterface Returns this document interface
     */
    public function incrementNumberDownloads();

    /**
     * Determines if this document is a directory.
     *
     * @return bool Returns true in case of success, false otherwise.
     */
    public function isDirectory();

    /**
     * Determines if this document is a document.
     *
     * @return bool Returns true in case of success, false otherwise.
     */
    public function isDocument();

    /**
     * Remove a child.
     *
     * @param DocumentInterface $child The child.
     * @return DocumentInterface Returns this document.
     */
    public function removeChild(DocumentInterface $child);

    /**
     * Save the parent.
     *
     * @return DocumentInterface Returns this document.
     */
    public function saveParent();

    /**
     * Set the created at.
     *
     * @param DateTime|null $createdAt The created at.
     * @return DocumentInterface Returns this document.
     */
    public function setCreatedAt(DateTime $createdAt = null);

    /**
     * Set the extension.
     *
     * @param string $extension The extension.
     * @return DocumentInterface Returns this document.
     */
    public function setExtension($extension);

    /**
     * Set the hash.
     *
     * @param string $hash The hash.
     * @return DocumentInterface Returns this document.
     */
    public function setHash($hash);

    /**
     * Set the mime type.
     *
     * @param string $mimeType The mime type.
     * @return DocumentInterface Returns this document.
     */
    public function setMimeType($mimeType);

    /**
     * Set the name.
     *
     * @param string $name The name.
     * @return DocumentInterface Returns this document.
     */
    public function setName($name);

    /**
     * Set the number of downloads.
     *
     * @param int $numberDownload The number of downloads.
     * @return DocumentInterface Returns this document.
     */
    public function setNumberDownloads($numberDownload);

    /**
     * Set the parent.
     *
     * @param DocumentInterface $document The parent.
     * @return DocumentInterface Returns this document.
     */
    public function setParent(DocumentInterface $document = null);

    /**
     * Set the size.
     *
     * @param float $size The size.
     * @return DocumentInterface Returns this document.
     */
    public function setSize($size);

    /**
     * Set the type.
     *
     * @param int $type The type.
     * @return DocumentInterface Returns this document.
     * @throws InvalidArgumentException Throws an invalid argument exception if the type is invalid.
     */
    public function setType($type);

    /**
     * Set the updated at.
     *
     * @param DateTime|null $updatedAt The updated at.
     * @return DocumentInterface Returns this document.
     */
    public function setUpdatedAt(DateTime $updatedAt = null);

    /**
     * Set the uploaded file.
     *
     * @param UploadedFile $uploadedFile The uploaded file.
     * @return DocumentInterface Returns this document.
     */
    public function setUploadedFile(UploadedFile $uploadedFile);
}
