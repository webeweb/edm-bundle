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
use WBW\Bundle\EDMBundle\Model\DocumentTrait;

/**
 * Document event.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Event
 */
class DocumentEvent extends AbstractEvent {

    use DocumentTrait;

    /**
     * Event "post delete".
     *
     * @var string
     */
    const POST_DELETE = "wbw.edm.event.document.post_delete";

    /**
     * Event "post download".
     *
     * @var string
     */
    const POST_DOWNLOAD = "wbw.edm.event.document.post_download";

    /**
     * Event "post edit".
     *
     * @var string
     */
    const POST_EDIT = "wbw.edm.event.document.post_edit";

    /**
     * Event "post move".
     *
     * @var string
     */
    const POST_MOVE = "wbw.edm.event.document.post_move";

    /**
     * Event "post new".
     *
     * @var string
     */
    const POST_NEW = "wbw.edm.event.document.post_new";

    /**
     * Event "pre delete".
     *
     * @var string
     */
    const PRE_DELETE = "wbw.edm.event.document.pre_delete";

    /**
     * Event "pre download".
     *
     * @var string
     */
    const PRE_DOWNLOAD = "wbw.edm.event.document.pre_download";

    /**
     * Event "pre edit".
     *
     * @var string
     */
    const PRE_EDIT = "wbw.edm.event.document.pre_edit";

    /**
     * Event "pre move".
     *
     * @var string
     */
    const PRE_MOVE = "wbw.edm.event.document.pre_move";

    /**
     * Event "pre new".
     *
     * @var string
     */
    const PRE_NEW = "wbw.edm.event.document.pre_new";

    /**
     * Response.
     *
     * @var Response|null
     */
    private $response;

    /**
     * Constructor.
     *
     * @param string $eventName The event name.
     * @param DocumentInterface $document The document.
     */
    public function __construct(string $eventName, DocumentInterface $document) {
        parent::__construct($eventName);

        $this->setDocument($document);
    }

    /**
     * Get the response.
     *
     * @return Response|null Returns the response.
     */
    public function getResponse(): ?Response {
        return $this->response;
    }

    /**
     * Set the response.
     *
     * @param Response|null $response The response.
     * @return DocumentEvent Returns this document event.
     */
    public function setResponse(?Response $response): DocumentEvent {
        $this->response = $response;
        return $this;
    }
}
