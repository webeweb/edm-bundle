<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Fixtures\Entity;

use WBW\Bundle\EDMBundle\Entity\Document;

/**
 * Test document entity.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Fixtures\Entity
 */
class TestDocument extends Document {

    /**
     * Set the id.
     *
     * @param int $id The id.
     */
    public function setId($id) {
        $this->id = $id;
    }

}
