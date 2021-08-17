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
use WBW\Bundle\CoreBundle\Model\ActionResponse;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Translation\TranslatorInterface;

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
    protected function buildRedirectRoute(DocumentInterface $document): array {
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
     * @return DocumentEvent|null Returns the document event.
     */
    protected function dispatchDocumentEvent(string $eventName, DocumentInterface $document): ?DocumentEvent {
        return $this->dispatchEvent($eventName, new DocumentEvent($eventName, $document));
    }

    /**
     * Prepare an action response.
     *
     * @param int $status The status.
     * @param string $notify The notify.
     * @return ActionResponse Returns the action response.
     */
    protected function prepareActionResponse(int $status, string $notify): ActionResponse {

        $response = new ActionResponse();
        $response->setStatus($status);
        $response->setNotify($this->translate($notify, [], TranslatorInterface::DOMAIN));

        return $response;
    }
}
