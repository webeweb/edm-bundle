<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;
use WBW\Bundle\EDMBundle\Entity\Document;

/**
 * Document form type.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type
 */
class DocumentFormType extends AbstractDocumentFormType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $disabled = $options["disabled"];

        $builder
            ->add("name", TextType::class, ["label" => "label.name", "disabled" => $disabled, "required" => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            "data_class"         => Document::class,
            "translation_domain" => "WBWEDMBundle",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return parent::getBlockPrefix();
    }
}
