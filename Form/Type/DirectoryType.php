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

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\DataTransformer\DocumentToStringTransformer;

/**
 * Directory type.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type
 * @final
 */
final class DirectoryType extends AbstractType {

	/**
	 * Service name.
	 *
	 * @var string
	 */
	const SERVICE_NAME = "webeweb.bundle.edmbundle.form.type.document";

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
	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder
			->add("name", TextType::class, ["label" => "label.name", "required" => false])
			->add("extensionBackedUp", HiddenType::class, [])
			->add("nameBackedUp", HiddenType::class, [])
			->add("parentBackedUp", HiddenType::class)
			->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

				// Get the entity.
				$directory = $event->getData();

				// Backup the necessary fields.
				$directory->setExtensionBackedUp($directory->getExtension());
				$directory->setNameBackedUp($directory->getName());
				$directory->setParentBackedUp($directory->getParent());
			});

		$builder
			->get("parentBackedUp")
			->addModelTransformer(new DocumentToStringTransformer($this->manager));
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			"data_class"		 => Document::class,
			"translation_domain" => "EDMBundle",
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix() {
		return "edmbundle_directory";
	}

}
