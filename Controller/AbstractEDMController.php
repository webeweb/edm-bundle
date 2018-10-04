<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Controller;

use WBW\Bundle\BootstrapBundle\Controller\AbstractBootstrapController;

/**
 * Abstract EDM controller.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 * @abstract
 */
abstract class AbstractEDMController extends AbstractBootstrapController {

    /**
     * Get the notification.
     *
     * @param string $id The notification id.
     * @return string Returns the notification.
     */
    protected function getNotification($id) {
        return $this->getTranslator()->trans($id, [], "EDMBundle");
    }

}
