<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Exception;

/**
 * None registered storage provider exception.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Exception
 */
class NoneRegisteredStorageProviderException extends AbstractException {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct("None registered storage provider");
    }
}
