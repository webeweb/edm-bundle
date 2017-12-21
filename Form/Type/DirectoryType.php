<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Library\Core\Form\Factory\FormFactory;

/**
 * Directory type.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type
 * @final
 */
final class DirectoryType extends AbstractType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$parent = FormFactory::createEntityType(Document::class, $options["entity.parent"], ["empty" => true]);
		$builder
			->add("parent", EntityType::class, array_merge(["label" => "label.parent", "required" => false], $parent))
			->add("name", TextType::class, ["label" => "label.name"]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			"data_class" => Document::class,
		]);
		$resolver->setRequired("entity.parent");
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix() {
		return "edmbundle_directory";
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		// Fix compatibility for Symfony 2.6.* and 2.7.*
		return get_class($this);
	}

}
