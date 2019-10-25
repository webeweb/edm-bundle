<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Repository\DocumentRepository;
use WBW\Bundle\EDMBundle\Tests\AbstractWebTestCase;
use WBW\Bundle\EDMBundle\Tests\Fixtures\TestFixtures;

/**
 * Document repository test.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Repository
 */
class DocumentRepositoryTest extends AbstractWebTestCase {

    /**
     * Document.
     *
     * @var DocumentInterface
     */
    private $document;

    /**
     * Document repository.
     *
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * {@inheritDoc}
     */
    protected function setUp() {
        parent::setUp();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");

        // Set a Document repository.
        $this->documentRepository = $em->getRepository(Document::class);

        // Set a Document mock.
        $this->document = $this->documentRepository->find(1);
    }

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");

        foreach (TestFixtures::getDocuments() as $current) {
            $em->persist($current);
        }

        $em->flush();
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function testConstruct() {

        $obj = $this->documentRepository;

        $this->assertInstanceOf(DocumentRepository::class, $obj);
    }

    /**
     * Tests the findAllByParent() method.
     *
     * @return void
     */
    public function testFindAllByParent() {

        $obj = $this->documentRepository;

        $res = $obj->findAllByParent($this->document);
        $this->assertCount(9, $res);

        $this->assertEquals("Applications", $res[0]->getName());
        $this->assertEquals("Desktop", $res[1]->getName());
        $this->assertEquals("Documents", $res[2]->getName());
        $this->assertEquals("Downloads", $res[3]->getName());
        $this->assertEquals("Music", $res[4]->getName());
        $this->assertEquals("Pictures", $res[5]->getName());
        $this->assertEquals("Public", $res[6]->getName());
        $this->assertEquals("Templates", $res[7]->getName());
        $this->assertEquals("Videos", $res[8]->getName());
    }

    /**
     * Tests the findAllByParent() method.
     *
     * @return void
     */
    public function testFindAllByParentWithNull() {

        $obj = $this->documentRepository;

        $res = $obj->findAllByParent();
        $this->assertCount(1, $res);

        $this->assertSame($this->document, $res[0]);
    }

    /**
     * Tests the findAllDirectoriesExcept() method.
     *
     * @return void
     */
    public function testFindAllDirectoriesExcept() {

        // Get a Document mock.
        $document = $this->documentRepository->find(10);

        $obj = $this->documentRepository;

        $res = $obj->findAllDirectoriesExcept($document);
        $this->assertCount(9, $res);

        $this->assertEquals("Applications", $res[0]->getName());
        $this->assertEquals("Desktop", $res[1]->getName());
        $this->assertEquals("Documents", $res[2]->getName());
        $this->assertEquals("Downloads", $res[3]->getName());
        $this->assertEquals("Home", $res[4]->getName());
        $this->assertEquals("Music", $res[5]->getName());
        $this->assertEquals("Pictures", $res[6]->getName());
        $this->assertEquals("Public", $res[7]->getName());
        $this->assertEquals("Templates", $res[8]->getName());
    }

    /**
     * Tests the findAllDirectoriesExcept() method.
     *
     * @return null
     */
    public function testFindAllDirectoriesExceptWithNull() {

        $obj = $this->documentRepository;

        $res = $obj->findAllDirectoriesExcept();
        $this->assertCount(10, $res);

        $this->assertEquals("Applications", $res[0]->getName());
        $this->assertEquals("Desktop", $res[1]->getName());
        $this->assertEquals("Documents", $res[2]->getName());
        $this->assertEquals("Downloads", $res[3]->getName());
        $this->assertEquals("Home", $res[4]->getName());
        $this->assertEquals("Music", $res[5]->getName());
        $this->assertEquals("Pictures", $res[6]->getName());
        $this->assertEquals("Public", $res[7]->getName());
        $this->assertEquals("Templates", $res[8]->getName());
        $this->assertEquals("Videos", $res[9]->getName());
    }

    /**
     * Tests the findAllDocumentsByParent() method.
     *
     * @return void
     */
    public function testFindAllDocumentsByParent() {

        $obj = $this->documentRepository;

        $res = $obj->findAllDocumentsByParent($this->document);
        $this->assertCount(0, $res);
    }

    /**
     * Tests the findAllDocumentsByParent() method.
     *
     * @return void
     */
    public function testFindAllDocumentsByParentWithNull() {

        $obj = $this->documentRepository;

        $res = $obj->findAllDocumentsByParent();
        $this->assertCount(0, $res);
    }
}
