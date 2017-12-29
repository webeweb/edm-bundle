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
use WBW\Bundle\EDMBundle\Entity\Document;

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
	 * @var Document
	 */
	private $document;

	/**
	 * Constructor.
	 *
	 * @param Document $document The document.
	 */
	public function __construct(Document $document) {
		$this->document = $document;
	}

	/**
	 * Get the document.
	 *
	 * @return Document Returns the document.
	 */
	public function getDocument() {
		return $this->document;
	}

}
