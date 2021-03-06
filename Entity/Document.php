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

use WBW\Bundle\CoreBundle\Entity\ChoiceLabelInterface;
use WBW\Bundle\EDMBundle\Model\AbstractDocument;

/**
 * Document.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Entity
 */
class Document extends AbstractDocument implements ChoiceLabelInterface {

    /**
     * {@inheritdoc}
     */
    public function getChoiceLabel(): ?string {
        return $this->getName();
    }
}
