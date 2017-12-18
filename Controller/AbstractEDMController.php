<?php

/**
 * Disclaimer: This source code is protected by copyright law and by
 * international conventions.
 *
 * Any reproduction or partial or total distribution of the source code, by any
 * means whatsoever, is strictly forbidden.
 *
 * Anyone not complying with these provisions will be guilty of the offense of
 * infringement and the penal sanctions provided for by law.
 *
 * © 2017 All rights reserved.
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
	protected final function notify(Request $request, $type, $message) {
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
	protected final function translate($id, array $parameters = [], $domain = null, $locale = null) {
		return $this->get("translator")->trans($id, $parameters, $domain, $locale);
	}

}
