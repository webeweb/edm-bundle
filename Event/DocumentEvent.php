<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Event;

use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\CoreBundle\Event\AbstractEvent;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;

/**
 * Document event.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Event
 */
class DocumentEvent extends AbstractEvent {

    /**
     * Document.
     *
     * @var DocumentInterface
     */
    private $document;

    /**
     * Response.
     *
     * @var Response
     */
    private $response;

    /**
     * Constructor.
     *
     * @param string $eventName The event name.
     * @param DocumentInterface $document The document.
     */
    public function __construct($eventName, DocumentInterface $document) {
        parent::__construct($eventName);
        $this->setDocument($document);
    }

    /**
     * Get the document.
     *
     * @return DocumentInterface Returns the document.
     */
    public function getDocument() {
        return $this->document;
    }

    /**
     * Get the response.
     *
     * @return Response Returns the response.
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * Set the document.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentEvent Returns this document event.
     */
    protected function setDocument(DocumentInterface $document) {
        $this->document = $document;
        return $this;
    }

    /**
     * Set the response.
     *
     * @param Response $response The response.
     * @return DocumentEvent Returns this document event.
     */
    public function setResponse(Response $response) {
        $this->response = $response;
        return $this;
    }
}
