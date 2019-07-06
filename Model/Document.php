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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Model
 */
class Document implements DocumentInterface {

    /**
     * Children
     *
     * @var Collection
     */
    private $children;

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
     * @var int
     */
    private $numberDownloads;

    /**
     * Parent.
     *
     * @var DocumentInterface
     */
    private $parent;

    /**
     * Size.
     *
     * @var float
     */
    private $size;

    /**
     * Type.
     *
     * @var int
     */
    private $type;

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
    private $uploadedFile;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->setChildren(new ArrayCollection());
        $this->setNumberDownloads(0);
        $this->setSize(0);
        $this->setType(self::TYPE_DOCUMENT);
    }

    /**
     * {@inheritDoc}
     */
    public function addChild(DocumentInterface $child) {
        $this->children[] = $child;
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
     * {@inheritDoc}
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * {@inheritDoc}
     */
    public function getName() {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getNumberDownloads() {
        return $this->numberDownloads;
    }

    /**
     * {@inheritDoc}
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * {@inheritDoc}
     */
    public function getType() {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFile() {
        return $this->uploadedFile;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren() {
        return 0 < count($this->children);
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
    public function incrementNumberDownloads() {
        ++$this->numberDownloads;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isDirectory() {
        return self::TYPE_DIRECTORY === $this->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function isDocument() {
        return self::TYPE_DOCUMENT === $this->getType();
    }

    /**
     * {@inheritDoc}
     */
    public function removeChild(DocumentInterface $child) {
        $this->children->removeElement($child);
        return $this;
    }

    /**
     * Set the children.
     *
     * @param Collection $children The children.
     * @return DocumentInterface Returns this document.
     */
    protected function setChildren(Collection $children) {
        $this->children = $children;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(DateTime $createdAt = null) {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setExtension($extension) {
        $this->extension = $extension;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setNumberDownloads($numberDownloads) {
        $this->numberDownloads = $numberDownloads;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setParent(DocumentInterface $parent = null) {
        $this->parent = $parent;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setSize($size) {
        $this->size = $size;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setType($type) {
        if (false === in_array($type, [self::TYPE_DIRECTORY, self::TYPE_DOCUMENT])) {
            throw new InvalidArgumentException(sprintf("The type \"%s\" is invalid", $type));
        }
        $this->type = $type;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setUpdatedAt(DateTime $updatedAt = null) {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setUploadedFile(UploadedFile $uploadedFile) {
        $this->uploadedFile = $uploadedFile;
        return $this;
    }
}
