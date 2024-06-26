<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Event;

use WBW\Bundle\CommonBundle\Event\AbstractEvent;
use WBW\Bundle\CommonBundle\HttpFoundation\ResponseTrait;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Model\DocumentTrait;

/**
 * Document event.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Event
 */
class DocumentEvent extends AbstractEvent {

    use DocumentTrait;
    use ResponseTrait {
        setResponse as public;
    }

    /**
     * Event "post delete".
     *
     * @var string
     */
    public const POST_DELETE = "wbw.edm.event.document.post_delete";

    /**
     * Event "post download".
     *
     * @var string
     */
    public const POST_DOWNLOAD = "wbw.edm.event.document.post_download";

    /**
     * Event "post edit".
     *
     * @var string
     */
    public const POST_EDIT = "wbw.edm.event.document.post_edit";

    /**
     * Event "post move".
     *
     * @var string
     */
    public const POST_MOVE = "wbw.edm.event.document.post_move";

    /**
     * Event "post new".
     *
     * @var string
     */
    public const POST_NEW = "wbw.edm.event.document.post_new";

    /**
     * Event "pre delete".
     *
     * @var string
     */
    public const PRE_DELETE = "wbw.edm.event.document.pre_delete";

    /**
     * Event "pre download".
     *
     * @var string
     */
    public const PRE_DOWNLOAD = "wbw.edm.event.document.pre_download";

    /**
     * Event "pre edit".
     *
     * @var string
     */
    public const PRE_EDIT = "wbw.edm.event.document.pre_edit";

    /**
     * Event "pre move".
     *
     * @var string
     */
    public const PRE_MOVE = "wbw.edm.event.document.pre_move";

    /**
     * Event "pre new".
     *
     * @var string
     */
    public const PRE_NEW = "wbw.edm.event.document.pre_new";

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
}
