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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
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
	 * Id.
	 *
	 * @var int
	 */
	private $id;

	/**
	 * Name.
	 *
	 * @var string
	 * @Assert\NotNull(message="document.name.notNull.message")
	 * @Assert\Length(
	 *   max=255,
	 *   maxMessage="document.name.length.maxMessage"
	 * )
	 */
	private $name;

	/**
	 * Old name.
	 *
	 * @var string
	 */
	private $oldName;

	/**
	 * Old parent.
	 *
	 * @var string
	 */
	private $oldParent;

	/**
	 * Parent.
	 *
	 * @var Document
	 */
	private $parent;

	/**
	 * Size.
	 *
	 * @var integer
	 */
	private $size;

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
	 * Backup.
	 *
	 * @return void
	 */
	public function backup() {
		$this->oldName	 = $this->name;
		$this->oldParent = $this->parent;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAlphabeticalTreeSortLabel() {
		return $this->name;
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
	 * Get the old name.
	 *
	 * @return string Returns the old name.
	 */
	public function getOldName() {
		return $this->oldName;
	}

	/**
	 * Get the old parent.
	 *
	 * @return Document Returns the old parent.
	 */
	public function getOldParent() {
		return $this->oldParent;
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
