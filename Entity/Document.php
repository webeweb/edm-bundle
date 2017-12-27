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
	 * Extension backed up.
	 *
	 * @var string
	 */
	private $extensionBackedUp;

	/**
	 * Id.
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Name backed up.
	 *
	 * @var string
	 */
	private $nameBackedUp;

	/**
	 * Parent.
	 *
	 * @var Document
	 */
	private $parent;

	/**
	 * Parent backed up.
	 *
	 * @var string
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
		$this->childrens[] = $children;
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
	 * Get the extension backed up.
	 *
	 * @return string Returns the extension backed up.
	 */
	public function getExtensionBackedUp() {
		return $this->extensionBackedUp;
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
	 * Get the name.
	 *
	 * @return string Returns the name.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get the name backed up.
	 *
	 * @return string Returns the name backed up.
	 */
	public function getNameBackedUp() {
		return $this->nameBackedUp;
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
			throw new ForeignKeyConstraintViolationException("The directory is not empty", new OCI8Exception("Self generated exception"));
		}
	}

	/**
	 * Remove a children.
	 *
	 * @param Document $children The children.
	 * @return Document Returns the document.
	 */
	public function removeChildren(Document $children) {
		$this->childrens->removeElement($children);
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
	 * Set the extension backed up.
	 *
	 * @param string $extensionBackedUp The extension backed up.
	 * @return Document Returns the document.
	 */
	public function setExtensionBackedUp($extensionBackedUp) {
		$this->extensionBackedUp = $extensionBackedUp;
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
	 * Set the name backed up.
	 *
	 * @param string $nameBackedUp The name backed up.
	 * @return Document Returns the document.
	 */
	public function setNameBackedUp($nameBackedUp) {
		$this->nameBackedUp = $nameBackedUp;
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
				$this->type	 = $type;
				break;
			default:
				$this->type	 = null;
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
