<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use WBW\Bundle\EDMBundle\Entity\Document;

/**
 * Document to string transformer.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type
 * @final
 */
final class DocumentToStringTransformer implements DataTransformerInterface {

	/**
	 * Entity manager.
	 *
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * Constructor.
	 *
	 * @param ObjectManager $manager The entity manager.
	 */
	public function __construct(ObjectManager $manager) {
		$this->manager = $manager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function reverseTransform($id) {
		if (is_null($id)) {
			return null;
		}
		$document = $this->manager->getRepository(Document::class)->find($id);
		if (is_null($document)) {
			throw new TransformationFailedException(sprintf("The document with id \"%s\" does not exist !", $id));
		}
		return $document;
	}

	/**
	 * {@inheritdoc}
	 */
	public function transform($document) {
		if (is_null($document)) {
			return "";
		}
		return $document->getId();
	}

}
