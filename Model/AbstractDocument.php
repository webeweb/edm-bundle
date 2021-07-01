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
use Doctrine\DBAL\Driver\OCI8\OCI8Exception;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Library\Traits\DateTimes\DateTimeCreatedAtTrait;
use WBW\Library\Traits\DateTimes\DateTimeUpdatedAtTrait;
use WBW\Library\Traits\Floats\FloatSizeTrait;
use WBW\Library\Traits\Integers\IntegerIdTrait;
use WBW\Library\Traits\Integers\IntegerTypeTrait;
use WBW\Library\Traits\Strings\StringExtensionTrait;
use WBW\Library\Traits\Strings\StringHashMd5Trait;
use WBW\Library\Traits\Strings\StringHashSha1Trait;
use WBW\Library\Traits\Strings\StringHashSha256Trait;
use WBW\Library\Traits\Strings\StringMimeTypeTrait;
use WBW\Library\Traits\Strings\StringNameTrait;
use WBW\Library\Core\Sorter\AlphabeticalTreeNodeInterface;

/**
 * Abstract document.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Model
 * @abstract
 */
abstract class AbstractDocument implements DocumentInterface, AlphabeticalTreeNodeInterface {

    use DateTimeCreatedAtTrait;
    use DateTimeUpdatedAtTrait;
    use FloatSizeTrait;
    use IntegerIdTrait;
    use IntegerTypeTrait;
    use StringExtensionTrait;
    use StringHashMd5Trait;
    use StringHashSha1Trait;
    use StringHashSha256Trait;
    use StringMimeTypeTrait;
    use StringNameTrait;

    /**
     * Children.
     *
     * @var Collection
     */
    protected $children;

    /**
     * Number of downloads.
     *
     * @var int|null
     */
    protected $numberDownloads;

    /**
     * Parent.
     *
     * @var DocumentInterface|null
     */
    protected $parent;

    /**
     * Saved parent.
     *
     * @var DocumentInterface|null
     */
    protected $savedParent;

    /**
     * Upload.
     *
     * @var UploadedFile|null
     */
    protected $uploadedFile;

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
    public function addChild(DocumentInterface $child): DocumentInterface {
        $this->children[] = $child;
        $child->setParent($this);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function decreaseSize(?float $size): DocumentInterface {
        $this->size -= $size;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlphabeticalTreeNodeLabel(): ?string {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getAlphabeticalTreeNodeParent(): ?AlphabeticalTreeNodeInterface {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     */
    public function getChildren(): Collection {
        return $this->children;
    }

    /**
     * {@inheritDoc}
     */
    public function getNumberDownloads(): ?int {
        return $this->numberDownloads;
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): ?DocumentInterface {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     */
    public function getSavedParent(): ?DocumentInterface {
        return $this->savedParent;
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFile(): ?UploadedFile {
        return $this->uploadedFile;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren(): bool {
        return 0 < count($this->children);
    }

    /**
     * {@inheritdoc}
     */
    public function increaseSize(?float $size): DocumentInterface {
        $this->size += $size;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementNumberDownloads(): DocumentInterface {
        ++$this->numberDownloads;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isDirectory(): bool {
        return self::TYPE_DIRECTORY === $this->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function isDocument(): bool {
        return self::TYPE_DOCUMENT === $this->getType();
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array {
        return DocumentHelper::serialize($this);
    }

    /**
     * Pre remove
     *
     * @return void
     * @throws ForeignKeyConstraintViolationException Throws a Foreign key constraint violation exception if the directory is not empty.
     */
    public function preRemove(): void {
        if (true === $this->hasChildren()) {
            throw new ForeignKeyConstraintViolationException("This directory is not empty", new OCI8Exception("Self generated exception"));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function removeChild(DocumentInterface $child): DocumentInterface {
        $this->children->removeElement($child);
        $child->setParent($this);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function saveParent(): DocumentInterface {
        $this->savedParent = $this->parent;
        return $this;
    }

    /**
     * Set the children.
     *
     * @param Collection $children The children.
     * @return DocumentInterface Returns this document.
     */
    protected function setChildren(Collection $children): DocumentInterface {
        $this->children = $children;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setNumberDownloads(?int $numberDownloads): DocumentInterface {
        $this->numberDownloads = $numberDownloads;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setParent(?DocumentInterface $parent): DocumentInterface {
        $this->parent = $parent;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setType(?int $type): DocumentInterface {
        if (false === in_array($type, [self::TYPE_DIRECTORY, self::TYPE_DOCUMENT])) {
            throw new InvalidArgumentException(sprintf('The type "%s" is invalid', $type));
        }
        $this->type = $type;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setUploadedFile(?UploadedFile $uploadedFile): DocumentInterface {
        $this->uploadedFile = $uploadedFile;
        return $this;
    }
}
