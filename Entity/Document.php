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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Driver\OCI8\OCI8Exception;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\CoreBundle\Entity\ChoiceLabelInterface;
use WBW\Bundle\CoreBundle\Model\Attribute\DateTimeCreatedAtTrait;
use WBW\Bundle\CoreBundle\Model\Attribute\DateTimeUpdatedAtTrait;
use WBW\Bundle\CoreBundle\Model\Attribute\IntegerIdTrait;
use WBW\Bundle\CoreBundle\Model\Attribute\IntegerSizeTrait;
use WBW\Bundle\CoreBundle\Model\Attribute\IntegerTypeTrait;
use WBW\Bundle\CoreBundle\Model\Attribute\StringExtensionTrait;
use WBW\Bundle\CoreBundle\Model\Attribute\StringMimeTypeTrait;
use WBW\Bundle\CoreBundle\Model\Attribute\StringNameTrait;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Library\Core\Sorting\AlphabeticalTreeNodeInterface;

/**
 * Document.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Entity
 */
class Document implements DocumentInterface, AlphabeticalTreeNodeInterface, ChoiceLabelInterface {

    use DateTimeCreatedAtTrait;
    use DateTimeUpdatedAtTrait;
    use IntegerIdTrait;
    use IntegerSizeTrait;
    use IntegerTypeTrait;
    use StringExtensionTrait;
    use StringMimeTypeTrait;
    use StringNameTrait;

    /**
     * Children
     *
     * @var Collection
     */
    private $children;

    /**
     * Hash.
     *
     * @var string
     */
    private $hash;

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
     * Saved parent.
     *
     * @var DocumentInterface
     */
    private $savedParent;

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
        $this->setCreatedAt(new DateTime());
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
        $child->setParent($this);
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
    public function getAlphabeticalTreeNodeLabel() {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlphabeticalTreeNodeParent() {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function getChoiceLabel() {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getHash() {
        return $this->hash;
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
    public function getSavedParent() {
        return $this->savedParent;
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
     * Pre remove
     *
     * @return void
     * @throws ForeignKeyConstraintViolationException Throws a Foreign key constraint violation exception if the directory is not empty.
     */
    public function preRemove() {
        if (true === $this->hasChildren()) {
            throw new ForeignKeyConstraintViolationException("This directory is not empty", new OCI8Exception("Self generated exception"));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function removeChild(DocumentInterface $child) {
        $this->children->removeElement($child);
        $child->setParent($this);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function saveParent() {
        $this->savedParent = $this->parent;
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
    public function setHash($hash) {
        $this->hash = $hash;
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
    public function setUploadedFile(UploadedFile $uploadedFile) {
        $this->uploadedFile = $uploadedFile;
        return $this;
    }
}
