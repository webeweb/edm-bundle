<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document interface.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Entity
 */
interface DocumentInterface {

    /**
     * Type "Directory".
     *
     * @var int
     */
    const TYPE_DIRECTORY = 117;

    /**
     * Type "Document".
     *
     * @var int
     */
    const TYPE_DOCUMENT = 95;

    /**
     * Decrease the size.
     *
     * @param int $size The size.
     * @return DocumentInterface Returns the document.
     */
    public function decreaseSize($size);

    /**
     * Get the childrens.
     *
     * @return Collection Returns the childrens.
     */
    public function getChildrens();

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
     * @return string Returns the number of downloads.
     */
    public function getNumberDownloads();

    /**
     * Get the parent.
     *
     * @return DocumentInterface Returns the parent.
     */
    public function getParent();

    /**
     * Get the parent backed up.
     *
     * @return DocumentInterface Returns the parent backed up.
     */
    public function getParentBackedUp();

    /**
     * Get the size.
     *
     * @return int Returns the size.
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
     * Get the upload.
     *
     * @return UploadedFile Returns the upload file.
     */
    public function getUpload();

    /**
     * Determines if the document has childrens.
     *
     * @return bool Returns true in case of success, false otherwise.
     */
    public function hasChildrens();

    /**
     * Increase the size.
     *
     * @param int $size The size.
     * @return DocumentInterface Returns the document.
     */
    public function increaseSize($size);

    /**
     * Increments the number of downloads.
     *
     * @return DocumentInterface Returns the document.
     */
    public function incrementNumberDownloads();

    /**
     * Determines if the document is a directory.
     *
     * @return bool Returns true in case of success, false otherwise.
     */
    public function isDirectory();

    /**
     * Determines if the document is a document.
     *
     * @return bool Returns true in case of success, false otherwise.
     */
    public function isDocument();
}
