<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Exception;

use WBW\Bundle\CommonBundle\Exception\AbstractException as BaseException;

/**
 * Abstract exception.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Exception
 * @abstract
 */
abstract class AbstractException extends BaseException {

    /**
     * Constructor.
     *
     * @param string $message The message.
     */
    public function __construct(string $message) {
        parent::__construct($message, 500);
    }
}
