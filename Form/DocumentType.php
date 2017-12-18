<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Entity\Document;

/**
 * Document type.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form
 * @final
 */
final class DocumentType extends AbstractType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add("parent", EntityType::class, ["label" => "label.directory"])
			->add("upload", FileType::class, ["label" => "label.file"]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			"data_class" => Document::class,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix() {
		return "edmbundle_document";
	}

}
