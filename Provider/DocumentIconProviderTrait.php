<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider;

/**
 * Document icon provider trait.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider
 */
trait DocumentIconProviderTrait {

    /**
     * Document icon provider.
     *
     * @var DocumentIconProvider
     */
    private $documentIconProvider;

    /**
     * Get the document icon provider.
     *
     * @return DocumentIconProvider Returns the document icon provider.
     */
    public function getDocumentIconProvider() {
        return $this->documentIconProvider;
    }

    /**
     * Set the document icon provider.
     *
     * @param DocumentIconProvider|null $documentIconProvider The document icon provider.
     */
    public function setDocumentIconProvider(DocumentIconProvider $documentIconProvider = null) {
        $this->documentIconProvider = $documentIconProvider;
        return $this;
    }
}
