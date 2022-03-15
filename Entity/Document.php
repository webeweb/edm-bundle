<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Entity;

use WBW\Bundle\EDMBundle\Model\AbstractDocument as BaseDocument;
use WBW\Library\Symfony\Assets\ChoiceLabelInterface;

/**
 * Document.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Entity
 */
class Document extends BaseDocument implements ChoiceLabelInterface {

    /**
     * {@inheritdoc}
     */
    public function getChoiceLabel(): ?string {
        return $this->getName();
    }
}
