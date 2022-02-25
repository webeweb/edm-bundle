<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Translation;

use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Translation\TranslatorInterface;

/**
 * Translator interface test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Translation
 */
class TranslatorInterfaceTest extends AbstractTestCase {

    /**
     * Tests __construct()
     *
     * @return void
     */
    public function test__construct(): void {

        $this->assertEquals("WBWEDMBundle", TranslatorInterface::DOMAIN);
    }
}
