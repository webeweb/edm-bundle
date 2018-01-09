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

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Entity\Document;

/**
 * Upload document type.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type\Document
 * @final
 */
final class UploadDocumentType extends AbstractDocumentType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {

		// Initialize the constraints.
		$constraints = [
			new \Symfony\Component\Validator\Constraints\NotBlank(["message" => "document.upload.notBlank.message"])
		];

		// Add the fields.
		$builder
			->add("upload", FileType::class, ["constraints" => $constraints, "label" => "label.file", "required" => false])
			->addEventListener(FormEvents::SUBMIT, [$this, "onSubmit"]);
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
		return "edmbundle_upload_document";
	}

	/**
	 * On submit.
	 *
	 * @param FormEvent $event The form event.
	 * @return void
	 */
	public function onSubmit(FormEvent $event) {

		// Get the entity.
		$document = $event->getData();

		// Set the name.
		if (null !== $document && null !== $document->getUpload()) {
			$extension	 = "." . $document->getUpload()->getClientOriginalExtension();
			$filename	 = $document->getUpload()->getClientOriginalName();
			$document->setName(str_replace($extension, "", $filename));
		}
	}

}
