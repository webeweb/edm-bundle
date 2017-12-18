<?php

/**
 * Disclaimer: This source code is protected by copyright law and by
 * international conventions.
 *
 * Any reproduction or partial or total distribution of the source code, by any
 * means whatsoever, is strictly forbidden.
 *
 * Anyone not complying with these provisions will be guilty of the offense of
 * infringement and the penal sanctions provided for by law.
 *
 * © 2017 All rights reserved.
 */

namespace WBW\Bundle\EDMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Entity\Document;

/**
 * Directory type.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form
 * @final
 */
final class DirectoryType extends AbstractType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add("name", TextType::class, ["label" => "label.name"]);
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
		return "edmbundle_directory";
	}

}
