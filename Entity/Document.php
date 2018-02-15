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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Driver\OCI8\OCI8Exception;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Utility\DocumentUtility;
use WBW\Library\Core\Form\Renderer\ChoiceRendererInterface;
use WBW\Library\Core\Sort\Tree\Alphabetical\AlphabeticalTreeSortInterface;

/**
 * Document entity.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Entity
 */
class Document implements AlphabeticalTreeSortInterface, ChoiceRendererInterface, DocumentInterface, JsonSerializable {

    /**
     * Childrens.
     *
     * @var Collection
     */
    private $childrens;

    /**
     * Created at.
     *
     * @var DateTime
     */
    private $createdAt;

    /**
     * Extension.
     *
     * @var string
     */
    private $extension;

    /**
     * Id.
     *
     * @var int
     */
    protected $id;

    /**
     * Mime type.
     *
     * @var string
     */
    private $mimeType;

    /**
     * Name.
     *
     * @var string
     */
    private $name;

    /**
     * Number of downloads.
     *
     * @var integer
     */
    private $numberDownloads = 0;

    /**
     * Parent.
     *
     * @var Document
     */
    private $parent;

    /**
     * Parent backed up.
     *
     * @var Document
     */
    private $parentBackedUp;

    /**
     * Size.
     *
     * @var integer
     */
    private $size = 0;

    /**
     * Type.
     *
     * @var integer
     */
    private $type = self::TYPE_DOCUMENT;

    /**
     * Updated at.
     *
     * @var DateTime
     */
    private $updatedAt;

    /**
     * Upload.
     *
     * @var UploadedFile
     */
    private $upload;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->childrens = new ArrayCollection();
    }

    /**
     * Add a children.
     *
     * @param Document $children The children.
     * @return Document Returns the document.
     */
    public function addChildren(Document $children) {
        if (false === $this->childrens->contains($children)) {
            $this->childrens[] = $children;
            $children->parent  = $this;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function decreaseSize($size) {
        $this->size -= $size;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlphabeticalTreeSortLabel() {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlphabeticalTreeSortParent() {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildrens() {
        return $this->childrens;
    }

    /**
     * {@inheritdoc}
     */
    public function getChoiceLabel() {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * Get the filename.
     *
     * Alias of DocumentUtility::getFilename().
     *
     * @return string Returns the filename.
     * @see DocumentUtility::getFilename()
     */
    public function getFilename() {
        return DocumentUtility::getFilename($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumberDownloads() {
        return $this->numberDownloads;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getParentBackedUp() {
        return $this->parentBackedUp;
    }

    /**
     * Get the paths.
     *
     * Alias of DocumentUtility::getPaths().
     *
     * @param boolean $backedUp Backed up ?
     * @return Document[] Returns the paths.
     * @see DocumentUtility::getPathname()
     */
    public function getPaths($backedUp = false) {
        return DocumentUtility::getPaths($this, $backedUp);
    }

    /**
     * {@inheritdoc}
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function getType() {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpload() {
        return $this->upload;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildrens() {
        return 0 < count($this->childrens);
    }

    /**
     * {@inheritdoc}
     */
    public function incrementNumberDownloads() {
        ++$this->numberDownloads;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function increaseSize($size) {
        $this->size += $size;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isDirectory() {
        return self::TYPE_DIRECTORY === $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function isDocument() {
        return self::TYPE_DOCUMENT === $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() {
        return $this->toArray();
    }

    /**
     * Pre remove
     *
     * @return void
     * @throws ForeignKeyConstraintViolationException Throws a Foreign key constraint violation exception if the directory is not empty.
     */
    public function preRemove() {
        if (true === $this->hasChildrens()) {
            throw new ForeignKeyConstraintViolationException("This directory is not empty", new OCI8Exception("Self generated exception"));
        }
    }

    /**
     * Remove a children.
     *
     * @param Document $children The children.
     * @return Document Returns the document.
     */
    public function removeChildren(Document $children) {
        if (true === $this->childrens->contains($children)) {
            $this->childrens->removeElement($children);
            $children->parent = null;
        }
        return $this;
    }

    /**
     * Set the created.
     *
     * @param DateTime $createdAt The created.
     * @return Document Returns the document.
     */
    public function setCreatedAt(DateTime $createdAt) {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Set the extension.
     *
     * @param string $extension The extension.
     * @return Document Returns the document.
     */
    public function setExtension($extension) {
        $this->extension = $extension;
        return $this;
    }

    /**
     * Set the mime type.
     *
     * @param string $mimeType The mime type.
     * @return Document Returns the document.
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Set the name.
     *
     * @param string $name The name.
     * @return Document Returns the document.
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the number of downloads.
     *
     * @param integer $numberDownloads The number of downloads.
     * @return Document Returns the document.
     */
    public function setNumberDownloads($numberDownloads) {
        $this->numberDownloads = $numberDownloads;
        return $this;
    }

    /**
     * Set the parent.
     *
     * @param Document $parent The parent.
     * @return Document Returns the document.
     */
    public function setParent(Document $parent = null) {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Set the parent backed up.
     *
     * @param Document $parentBackedUp The parent backed up.
     * @return Document Returns the document.
     */
    public function setParentBackedUp(Document $parentBackedUp = null) {
        $this->parentBackedUp = $parentBackedUp;
        return $this;
    }

    /**
     * Set the size.
     *
     * @param integer $size The size.
     * @return Document Returns the document.
     */
    public function setSize($size) {
        $this->size = $size;
        return $this;
    }

    /**
     * Set the type.
     *
     * @param integer $type The type.
     * @return Document Returns the document.
     */
    public function setType($type) {
        switch ($type) {
            case self::TYPE_DIRECTORY:
            case self::TYPE_DOCUMENT:
                $this->type = $type;
                break;
            default:
                $this->type = null;
        }
        return $this;
    }

    /**
     * Set the updated at.
     *
     * @param DateTime $updatedAt The updated at.
     * @return Document Returns the document.
     */
    public function setUpdatedAt(DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Set the upload.
     *
     * @param UploadedFile $upload The upload.
     * @return Document Returns the document.
     */
    public function setUpload(UploadedFile $upload) {
        $this->upload = $upload;
        return $this;
    }

    /**
     * Convert into an array representing this instance.
     *
     * @return array Returns an array representing this instance.
     */
    public function toArray() {

        // Initialize the ouput.
        $output = [];

        $output["id"]              = $this->id;
        $output["createdAt"]       = $this->createdAt;
        $output["extension"]       = $this->extension;
        $output["filename"]        = DocumentUtility::getFilename($this);
        $output["mimeType"]        = $this->mimeType;
        $output["name"]            = $this->name;
        $output["numberDownloads"] = $this->numberDownloads;
        $output["size"]            = $this->size;
        $output["type"]            = $this->type;
        $output["updatedAt"]       = $this->updatedAt;

        // Return the output.
        return $output;
    }

}
