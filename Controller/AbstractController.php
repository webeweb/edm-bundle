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
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
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
     * Build a redirect route.
     *
     * @param DocumentInterface $document The document.
     * @return array Returns the redirect route.
     */
    protected function buildRedirectRoute(DocumentInterface $document) {
        return [
            "wbw_edm_document_index",
            [
                "id" => null === $document->getParent() ? null : $document->getParent()->getId(),
            ],
        ];
    }

    /**
     * Dispatch an event.
     *
     * @param string $eventName The event name.
     * @param DocumentInterface $document The document.
     * @return DocumentEvent Returns the document event.
     */
    protected function dispatchDocumentEvent($eventName, DocumentInterface $document) {
        return $this->dispatchEvent($eventName, new DocumentEvent($eventName, $document));
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
        $response->setNotify($this->translate($notify));

        // Return the action response.
        return $response;
    }

    /**
     * Translate.
     *
     * @param string $id The id.
     * @param array $parameters The parameters.
     * @return string Returns the translation.
     */
    protected function translate($id, array $parameters = []) {
        return $this->getTranslator()->trans($id, $parameters, "WBWEDMBundle");
    }
}
