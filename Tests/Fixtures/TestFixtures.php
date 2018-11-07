<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Fixtures;

use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Entity\DocumentInterface;

/**
 * Test fixtures.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Fixtures
 */
class TestFixtures {

    /**
     * Get the documents fixtures.
     *
     * @return DocumentInterface Returns the documents fixtures.
     */
    public static function getDocuments() {

        // Initialize an Applications directory.
        $applications = new Document();
        $applications->setName("Applications");
        $applications->setType(Document::TYPE_DIRECTORY);

        // Initialize a Desktop directory.
        $desktop = new Document();
        $desktop->setName("Desktop");
        $desktop->setType(Document::TYPE_DIRECTORY);

        // Initialize a Documents directory.
        $documents = new Document();
        $documents->setName("Documents");
        $documents->setType(Document::TYPE_DIRECTORY);

        // Initialize a Document directory.
        $downloads = new Document();
        $downloads->setName("Downloads");
        $downloads->setType(Document::TYPE_DIRECTORY);

        // Initialize a Music directory.
        $music = new Document();
        $music->setName("Music");
        $music->setType(Document::TYPE_DIRECTORY);

        $pictures = new Document();
        $pictures->setName("Pictures");
        $pictures->setType(Document::TYPE_DIRECTORY);

        // Initialize a Public directory.
        $public = new Document();
        $public->setName("Public");
        $public->setType(Document::TYPE_DIRECTORY);

        // Initialize a Templates directory.
        $templates = new Document();
        $templates->setName("Templates");
        $templates->setType(Document::TYPE_DIRECTORY);

        // Initialize a Videos directory.
        $videos = new Document();
        $videos->setName("Videos");
        $videos->setType(Document::TYPE_DIRECTORY);

        // Initialize an Home directory.
        $home = new Document();
        $home->setName("Home");
        $home->setType(Document::TYPE_DIRECTORY);

        $home->addChildren($applications);
        $home->addChildren($desktop);
        $home->addChildren($documents);
        $home->addChildren($downloads);
        $home->addChildren($music);
        $home->addChildren($pictures);
        $home->addChildren($public);
        $home->addChildren($templates);
        $home->addChildren($videos);

        // Return the documents fixtures.
        return $home;
    }

}
