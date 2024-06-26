<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Tests\Provider;

use WBW\Bundle\EDMBundle\Provider\DocumentIconProvider;
use WBW\Bundle\EDMBundle\Tests\AbstractTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\Provider\TestDocumentIconTrait;

/**
 * Document icon provider trait test.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Tests\Provider
 */
class DocumentIconProviderTraitTest extends AbstractTestCase {

    /**
     * Test setDocumentIconProvider()
     *
     * @return void
     */
    public function testSetDocumentIconProvider(): void {

        // Set a Document icon provider mock.
        $documentIconProvider = new DocumentIconProvider();

        $obj = new TestDocumentIconTrait();

        $obj->setDocumentIconProvider($documentIconProvider);
        $this->assertSame($documentIconProvider, $obj->getDocumentIconProvider());
    }
}
