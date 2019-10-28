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


    /**
     * Get the POST data.
     *
     * @return array Returns the POST data.
     */
    public static function getPOSTData() {

        $fixtures = [];

        // Name
        $fixtures["columns"][0]["data"]            = "name";
        $fixtures["columns"][0]["name"]            = "Name";
        $fixtures["columns"][0]["orderable"]       = "true";
        $fixtures["columns"][0]["search"]["regex"] = "false";
        $fixtures["columns"][0]["search"]["value"] = "";
        $fixtures["columns"][0]["searchable"]      = "true";

        // Size
        $fixtures["columns"][1]["data"]            = "size";
        $fixtures["columns"][1]["name"]            = "Size";
        $fixtures["columns"][1]["orderable"]       = "true";
        $fixtures["columns"][1]["search"]["regex"] = "false";
        $fixtures["columns"][1]["search"]["value"] = "";
        $fixtures["columns"][1]["searchable"]      = "true";

        // UpdatedAt
        $fixtures["columns"][2]["data"]            = "updatedAt";
        $fixtures["columns"][2]["name"]            = "Updated at";
        $fixtures["columns"][2]["orderable"]       = "true";
        $fixtures["columns"][2]["search"]["regex"] = "false";
        $fixtures["columns"][2]["search"]["value"] = "";
        $fixtures["columns"][2]["searchable"]      = "true";

        // Type
        $fixtures["columns"][3]["data"]            = "type";
        $fixtures["columns"][3]["name"]            = "Type";
        $fixtures["columns"][3]["orderable"]       = "true";
        $fixtures["columns"][3]["search"]["regex"] = "false";
        $fixtures["columns"][3]["search"]["value"] = "";
        $fixtures["columns"][3]["searchable"]      = "false";

        // Actions.
        $fixtures["columns"][6]["data"]            = "actions";
        $fixtures["columns"][6]["name"]            = "Actions";
        $fixtures["columns"][6]["orderable"]       = "false";
        $fixtures["columns"][6]["search"]["regex"] = "false";
        $fixtures["columns"][6]["search"]["value"] = "";
        $fixtures["columns"][6]["searchable"]      = "false";

        //
        $fixtures["draw"]   = "1";
        $fixtures["length"] = "-1";

        // Order
        $fixtures["order"][0]["column"] = "0";
        $fixtures["order"][0]["dir"]    = "asc";

        // Search
        $fixtures["search"]["regex"] = "false";
        $fixtures["search"]["value"] = "";

        // Start
        $fixtures["start"] = "0";

        return $fixtures;
    }
}
