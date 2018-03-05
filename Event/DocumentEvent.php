<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;

/**
 * Document event.
 *
 * @author webeweb <https://github.com/webeweb/>
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
