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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use WBW\Bundle\BootstrapBundle\Controller\AbstractController as BaseController;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\WBWEDMBundle;
use WBW\Library\Symfony\Response\SimpleJsonResponseData;
use WBW\Library\Symfony\Response\SimpleJsonResponseDataInterface;

/**
 * Abstract controller.
 *
 * @author webeweb <https://github.com/webeweb>
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
     * @throws Throwable Throws an exception if an error occurs.
     */
    protected function dispatchDocumentEvent(string $eventName, DocumentInterface $document): ?DocumentEvent {
        return $this->dispatchEvent($eventName, new DocumentEvent($eventName, $document));
    }

    /**
     * Find a document.
     *
     * @param int|null $id The document.
     * @param bool $ex Throws exception ?
     * @return DocumentInterface|null Returns the document.
     * @throws Throwable Throws an exception if an error occurs.
     */
    protected function findDocument(?int $id, bool $ex): ?DocumentInterface {

        $document = $this->getEntityManager()->getRepository(Document::class)->find($id);
        if (null === $document && true === $ex) {
            throw new NotFoundHttpException();
        }

        return $document;
    }

    /**
     * Prepare an action response.
     *
     * @param int $status The status.
     * @param string $notify The notify.
     * @return SimpleJsonResponseDataInterface Returns the action response.
     * @throws Throwable Throws an exception if an error occurs.
     */
    protected function prepareActionResponse(int $status, string $notify): SimpleJsonResponseDataInterface {

        $response = new SimpleJsonResponseData();
        $response->setStatus($status);
        $response->setNotify($this->translate($notify));

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected function translate(string $id, array $parameters = [], string $domain = null, string $locale = null): string {

        if (null === $domain) {
            $domain = WBWEDMBundle::getTranslationDomain();
        }

        return parent::translate($id, $parameters, $domain, $locale);
    }
}
