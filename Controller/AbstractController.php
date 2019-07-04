<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Controller;

use WBW\Bundle\BootstrapBundle\Controller\AbstractController as BaseController;
use WBW\Library\Core\Model\Response\ActionResponse;

/**
 * Abstract controller.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 * @abstract
 */
abstract class AbstractController extends BaseController {

    /**
     * Get the notification.
     *
     * @param string $id The notification id.
     * @return string Returns the notification.
     */
    protected function getNotification($id) {
        return $this->getTranslator()->trans($id, [], "EDMBundle");
    }

    /**
     * Prepare an action response.
     *
     * @param int $status The status.
     * @param string $notify The notify.
     * @return ActionResponse Returns the action response.
     */
    protected function prepareActionResponse($status, $notify) {

        // Initialize the action response.
        $response = new ActionResponse();
        $response->setStatus($status);
        $response->setNotify($this->getNotification($notify));

        // Return the action response.
        return $response;
    }

}
