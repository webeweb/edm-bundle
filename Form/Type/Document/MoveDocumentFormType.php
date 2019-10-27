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
use WBW\Bundle\CoreBundle\Form\Factory\FormFactory;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\AbstractDocumentFormType;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Library\Core\Sorting\AlphabeticalTreeSort;

/**
 * Move document form type.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Form\Type\Document
 */
class MoveDocumentFormType extends AbstractDocumentFormType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $disabled = $options["disabled"];

        $sorter = new AlphabeticalTreeSort(array_values($options["entity.parent"]));
        $sorter->sort();

        $parent = FormFactory::newEntityType(Document::class, $sorter->getNodes(), ["empty" => true]);

        $builder
            ->add("parent", EntityType::class, array_merge(["label" => "label.parent", "disabled" => $disabled, "required" => false], $parent))
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, "onPreSetData"]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            "data_class"         => Document::class,
            "translation_domain" => "WBWEDMBundle",
        ]);
        $resolver->setRequired("entity.parent");
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return parent::getBlockPrefix() . "_move";
    }

    /**
     * On pre set data.
     *
     * @param FormEvent $event The form event.
     * @return FormEvent Returns the form event.
     */
    public function onPreSetData(FormEvent $event) {

        /** @var DocumentInterface $document */
        $document = $event->getData();

        if (null !== $document) {
            $document->saveParent();
        }

        return $event;
    }
}
