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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Library\Core\Form\Renderer\ChoiceRendererInterface;
use WBW\Library\Core\Sort\Tree\Alphabetical\AlphabeticalTreeSortInterface;

/**
 * Document entity.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Entity
 */
class Document implements AlphabeticalTreeSortInterface, ChoiceRendererInterface, DocumentInterface {

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
     * Decrease the size.
     *
     * @param integer $size The size.
     * @return Document Returns the document.
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
     * Get the childrens.
     *
     * @return Collection Returns the childrens.
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
     * Get the created at.
     *
     * @return DateTime Returns the created at.
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Get the extension.
     *
     * @return string Returns the extension.
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * Get the filename.
     *
     * @return string Returns the filename.
     */
    public function getFilename() {
        if ($this->isDirectory()) {
            return $this->name;
        }
        $filename = implode(".", [$this->name, $this->extension]);
        return "." !== $filename ? $filename : "";
    }

    /**
     * Get the id.
     *
     * @return integer Returns the id.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the mime type.
     *
     * @return string Returns the mime type.
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * Get the name.
     *
     * @return string Returns the name.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get the number of downloads.
     *
     * @return string Returns the number of downloads.
     */
    public function getNumberDownloads() {
        return $this->numberDownloads;
    }

    /**
     * Get the parent.
     *
     * @return Document Returns the parent.
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Get the parent backed up.
     *
     * @return Document Returns the parent backed up.
     */
    public function getParentBackedUp() {
        return $this->parentBackedUp;
    }

    /**
     * Get the pathname.
     *
     * @return string Return the pathname.
     */
    public function getPathname() {
        $path = [];
        foreach ($this->getPaths(false) as $current) {
            $path[] = $current->getFilename();
        }
        return implode("/", $path);
    }

    /**
     * Get the paths.
     *
     * @param boolean $backedUp Backed up ?
     * @return Document[] Returns the path.
     */
    public function getPaths($backedUp = false) {

        // Initialize the path.
        $path = [];

        // Save the document.
        $current = $this;

        // Handle each parent.
        while (null !== $current) {
            array_unshift($path, $current);
            $current = $current === $this && true === $backedUp ? $current->getParentBackedUp() : $current->getParent();
        }

        // Return the path.
        return $path;
    }

    /**
     * Get the size.
     *
     * @return integer Returns the size.
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Get the type.
     *
     * @return integer Returns the type.
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Get the updated at.
     *
     * @return DateTime Returns the updated at.
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Get the upload.
     *
     * @return UplaodedFile Returns the upload file.
     */
    public function getUpload() {
        return $this->upload;
    }

    /**
     * Determines if the document has childrens.
     *
     * @return boolean Returns true in case of success, false otherwise.
     */
    public function hasChildrens() {
        return 0 < count($this->childrens);
    }

    /**
     * Increments the number of downloads.
     *
     * @return Document Returns the document.
     */
    public function incrementNumberDownloads() {
        ++$this->numberDownloads;
        return $this;
    }

    /**
     * Increase the size.
     *
     * @param integer $size The size.
     * @return Document Returns the document.
     */
    public function increaseSize($size) {
        $this->size += $size;
        return $this;
    }

    /**
     * Determines if the document is a directory.
     *
     * @return boolean Returns true in case of success, false otherwise.
     */
    public function isDirectory() {
        return false === $this->isDocument();
    }

    /**
     * Determines if the document is a document.
     *
     * @return boolean Returns true in case of success, false otherwise.
     */
    public function isDocument() {
        return self::TYPE_DOCUMENT === $this->type;
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

}
