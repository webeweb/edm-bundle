<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Fixtures\App;

use WBW\Bundle\EDMBundle\Event\DocumentEvent;

/**
 * Test listener.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Fixtures\App
 * @final
 */
final class TestListener {

    /**
     * On edited document.
     *
     * @param DocumentEvent $event The event.
     * @return void
     */
    public function onEditedDocument(DocumentEvent $event) {
        // NOTHING TO DO.
    }

    /**
     * On opened directory.
     *
     * @param DocumentEvent $event The event.
     * @return void
     */
    public function onOpenedDocument(DocumentEvent $event) {
        // NOTHING TO DO.
    }

}
