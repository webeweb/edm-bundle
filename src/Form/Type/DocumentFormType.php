<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\WBWEDMBundle;

/**
 * Document form type.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Form\Type
 */
class DocumentFormType extends AbstractDocumentFormType {

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $disabled = $options["disabled"];

        $builder
            ->add("name", TextType::class, [
                "disabled" => $disabled,
                "label"    => "label.name",
                "required" => false,
                "trim"     => true,
            ]);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            "data_class"         => Document::class,
            "translation_domain" => WBWEDMBundle::getTranslationDomain(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string {
        return parent::getBlockPrefix();
    }
}
