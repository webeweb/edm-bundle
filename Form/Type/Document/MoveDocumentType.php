<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Form\Type\Document;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Library\Core\Form\Factory\FormFactory;
use WBW\Library\Core\Sort\Tree\Alphabetical\AlphabeticalTreeSort;

/**
 * Move document type.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type\Document
 * @final
 */
final class MoveDocumentType extends AbstractDocumentType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$parent = FormFactory::createEntityType(Document::class, AlphabeticalTreeSort::sort(array_values($options["entity.parent"])), ["empty" => true]);
		$builder
			->add("parent", EntityType::class, array_merge(["label" => "label.parent", "required" => false], $parent))
			->addEventListener(FormEvents::PRE_SET_DATA, [$this, "preSetData"]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			"data_class"		 => Document::class,
			"translation_domain" => "EDMBundle",
		]);
		$resolver->setRequired("entity.parent");
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix() {
		return "edmbundle_move_document";
	}

}
