<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Tests\Fixtures\Model;

use WBW\Bundle\EDMBundle\Model\AbstractDocument;

/**
 * Test document entity.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Fixtures\Model
 */
class TestDocument extends AbstractDocument {

    /**
     * Set the id.
     *
     * @param int|null $id The id.
     * @return AbstractDocument Returns this document.
     */
    public function setId(?int $id): AbstractDocument {
        $this->id = $id;
        return $this;
    }
}
