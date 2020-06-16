<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
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
use Symfony\Component\Validator\Constraints\NotBlank;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\AbstractDocumentFormType;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Translation\TranslationInterface;

/**
 * Upload document form type.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type\Document
 */
class UploadDocumentFormType extends AbstractDocumentFormType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $constraints = [
            new NotBlank(["message" => "document.upload.not_blank.message"]),
        ];

        $disabled = $options["disabled"];

        $builder
            ->add("uploadedFile", FileType::class, ["constraints" => $constraints, "label" => "label.file", "disabled" => $disabled, "required" => false])
            ->addEventListener(FormEvents::SUBMIT, [$this, "onSubmit"]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            "csrf_protection"    => true,
            "data_class"         => Document::class,
            "translation_domain" => TranslationInterface::TRANSLATION_DOMAIN,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return parent::getBlockPrefix() . "_upload";
    }

    /**
     * On submit.
     *
     * @param FormEvent $event The form event.
     * @return FormEvent Returns the event.
     */
    public function onSubmit(FormEvent $event) {

        /** @var DocumentInterface $document */
        $document = $event->getData();

        if (null !== $document && null !== $document->getUploadedFile()) {

            $uploaded = $document->getUploadedFile();

            $extension = $uploaded->getClientOriginalExtension();
            $filename  = $uploaded->getClientOriginalName();
            $filesize  = filesize($uploaded->getPathname());
            $mimeType  = $uploaded->getClientMimeType();

            $document->setExtension($extension);
            $document->setSize($filesize);
            $document->setMimeType($mimeType);
            $document->setName(basename($filename, ".{$extension}"));

            $document->setHashMd5(hash_file("md5", $uploaded->getPathname()));
            $document->setHashSha1(hash_file("sha1", $uploaded->getPathname()));
            $document->setHashSha256(hash_file("sha256", $uploaded->getPathname()));

            if (null !== $document->getParent()) {
                $document->getParent()->increaseSize($document->getSize());
            }
        }

        return $event;
    }
}
