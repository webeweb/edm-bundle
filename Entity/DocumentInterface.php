<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Entity;

use DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document interface.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Entity
 */
interface DocumentInterface {

    /**
     * Type "Directory".
     *
     * @var integer
     */
    const TYPE_DIRECTORY = 117;

    /**
     * Type "Document".
     *
     * @var integer
     */
    const TYPE_DOCUMENT = 95;

    /**
     * Decrease the size.
     *
     * @param integer $size The size.
     * @return DocumentInterface Returns the document.
     */
    public function decreaseSize($size);

    /**
     * Get the childrens.
     *
     * @return DocumentInterface[] Returns the childrens.
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
     * @return integer Returns the id.
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
     * @return integer Returns the size.
     */
    public function getSize();

    /**
     * Get the type.
     *
     * @return integer Returns the type.
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
     * @return boolean Returns true in case of success, false otherwise.
     */
    public function hasChildrens();

    /**
     * Increments the number of downloads.
     *
     * @return DocumentInterface Returns the document.
     */
    public function incrementNumberDownloads();

    /**
     * Increase the size.
     *
     * @param integer $size The size.
     * @return DocumentInterface Returns the document.
     */
    public function increaseSize($size);

    /**
     * Determines if the document is a directory.
     *
     * @return boolean Returns true in case of success, false otherwise.
     */
    public function isDirectory();

    /**
     * Determines if the document is a document.
     *
     * @return boolean Returns true in case of success, false otherwise.
     */
    public function isDocument();
}
