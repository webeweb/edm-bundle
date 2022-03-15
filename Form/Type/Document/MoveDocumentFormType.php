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

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WBW\Bundle\CoreBundle\Factory\FormFactory;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\AbstractDocumentFormType;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Translation\TranslatorInterface;
use WBW\Library\Sorter\AlphabeticalTreeSort;

/**
 * Move document form type.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Form\Type\Document
 */
class MoveDocumentFormType extends AbstractDocumentFormType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $disabled = $options["disabled"];

        $sorter = new AlphabeticalTreeSort(array_values($options["entity.parent"]));
        $sorter->sort();

        $parent = FormFactory::newEntityType(Document::class, $sorter->getNodes());

        $builder
            ->add("parent", EntityType::class, array_merge([
                "disabled" => $disabled,
                "label"    => "label.parent",
                "required" => false,
            ], $parent))
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, "onPreSetData"]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            "data_class"         => Document::class,
            "translation_domain" => TranslatorInterface::DOMAIN,
        ]);
        $resolver->setRequired("entity.parent");
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string {
        return parent::getBlockPrefix() . "_move";
    }

    /**
     * On pre set data.
     *
     * @param FormEvent $event The form event.
     * @return FormEvent Returns the form event.
     */
    public function onPreSetData(FormEvent $event): FormEvent {

        /** @var DocumentInterface $document */
        $document = $event->getData();

        if (null !== $document) {
            $document->saveParent();
        }

        return $event;
    }
}
