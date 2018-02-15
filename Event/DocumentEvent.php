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

namespace WBW\Bundle\EDMBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;

/**
 * Document event.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Event
 * @final
 */
final class DocumentEvent extends Event {

    /**
     * Document.
     *
     * @var DocumentInterface
     */
    private $document;

    /**
     * Event name.
     *
     * @var string
     */
    private $eventName;

    /**
     * Constructor.
     *
     * @param string $eventName The event name.
     * @param DocumentInterface $document The document.
     */
    public function __construct($eventName, DocumentInterface $document) {
        $this->document  = $document;
        $this->eventName = $eventName;
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
     * Get the event name.
     *
     * @return string Returns the event name.
     */
    public function getName() {
        return $this->eventName;
    }

}
