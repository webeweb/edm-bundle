<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use WBW\Bundle\EDMBundle\DependencyInjection\WBWEDMExtension;

/**
 * Abstract document form type.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Form\Type
 * @abstract
 */
abstract class AbstractDocumentFormType extends AbstractType {

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string {
        return WBWEDMExtension::EXTENSION_ALIAS . "_document";
    }
}
