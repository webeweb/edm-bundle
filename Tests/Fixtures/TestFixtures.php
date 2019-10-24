<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Fixtures;

use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;

/**
 * Test fixtures.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Fixtures
 */
class TestFixtures {

    /**
     * Get the documents.
     *
     * @return DocumentInterface[] Returns the documents.
     */
    public static function getDocuments() {

        /** @var DocumentInterface[] $fixtures */
        $fixtures = [
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Home"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Applications"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Desktop"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Documents"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Downloads"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Music"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Pictures"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Public"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Templates"),
            (new Document())->setType(Document::TYPE_DIRECTORY)->setName("Videos"),
        ];

        $fixtures[0]->addChild($fixtures[1]);
        $fixtures[0]->addChild($fixtures[2]);
        $fixtures[0]->addChild($fixtures[3]);
        $fixtures[0]->addChild($fixtures[4]);
        $fixtures[0]->addChild($fixtures[5]);
        $fixtures[0]->addChild($fixtures[6]);
        $fixtures[0]->addChild($fixtures[7]);
        $fixtures[0]->addChild($fixtures[8]);
        $fixtures[0]->addChild($fixtures[9]);

        return $fixtures;
    }
}
