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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Library\Core\Utility\FileUtility;

/**
 * Document edit type.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type
 * @final
 */
final class DocumentEditType extends AbstractType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add("name", TextType::class, ["label" => "label.name", "required" => false])
			->add("upload", FileType::class, ["label" => "label.file"])
			->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

				// Get the entity.
				$document = $event->getData();

				// Backup the necessary fields.
				$document->setExtensionBackedUp($document->getExtension());
				$document->setNameBackedUp($document->getName());
				$document->setParentBackedUp($document->getParent());

				// Check the upload.
				if (!is_null($document->getUpload())) {
					$document->setExtension($document->getUpload()->guessExtension());
					$document->setSize(FileUtility::getSize($document->getUpload()->getPathname()));
				}

				// Check the parent.
				if (!is_null($document->getParent())) {
					$parent = $document->getParent();
					$parent->setSize($parent->getSize() + $document->getSize());
				}
			});
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
		return "edmbundle_document";
	}

}
