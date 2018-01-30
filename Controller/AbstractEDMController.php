<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract EDM controller.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 * @abstract
 */
abstract class AbstractEDMController extends Controller {

    /**
     * Notification "Danger".
     *
     * @var string
     */
    const NOTIFICATION_DANGER = "danger";

    /**
     * Notification "Info".
     *
     * @var string
     */
    const NOTIFICATION_INFO = "info";

    /**
     * Notification "Success".
     *
     * @var string
     */
    const NOTIFICATION_SUCCESS = "success";

    /**
     * Notification "Warning".
     *
     * @var string
     */
    const NOTIFICATION_WARNING = "warning";

    /**
     * Notify.
     *
     * @param Request $request The request.
     * @param string $type The type.
     * @param string $message The message.
     */
    final protected function notify(Request $request, $type, $message) {
        $request->getSession()->getFlashBag()->add($type, $message);
    }

    /**
     * Translate.
     *
     * @param string $id The id.
     * @param array $parameters The parameters.
     * @param string|null $domain The domain.
     * @param string|null $locale The locale.
     * @return string Returns the translation.
     */
    final protected function translate($id, array $parameters = [], $domain = null, $locale = null) {
        return $this->get("translator")->trans($id, $parameters, $domain, $locale);
    }

}
