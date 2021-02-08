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
    protected function setUp(): void {
        parent::setUp();

        /** @var EntityManagerInterface $em */
        $em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");

        // Set a Document repository.
        $this->documentRepository = $em->getRepository(Document::class);

        // Set a Document mock.
        $this->document = $this->documentRepository->find(1);
    }

    /**
     * Tests the findAllByParent() method.
     *
     * @return void
     */
    public function testFindAllByParent(): void {

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
    public function testFindAllByParentWithNull(): void {

        $obj = $this->documentRepository;

        $res = $obj->findAllByParent(null);
        $this->assertCount(1, $res);

        $this->assertSame($this->document, $res[0]);
    }

    /**
     * Tests the findAllDirectoriesExcept() method.
     *
     * @return void
     */
    public function testFindAllDirectoriesExcept(): void {

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
    public function testFindAllDirectoriesExceptWithNull(): void {

        $obj = $this->documentRepository;

        $res = $obj->findAllDirectoriesExcept(null);
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
    public function testFindAllDocumentsByParent(): void {

        $obj = $this->documentRepository;

        $res = $obj->findAllDocumentsByParent($this->document);
        $this->assertCount(0, $res);
    }

    /**
     * Tests the findAllDocumentsByParent() method.
     *
     * @return void
     */
    public function testFindAllDocumentsByParentWithNull(): void {

        $obj = $this->documentRepository;

        $res = $obj->findAllDocumentsByParent(null);
        $this->assertCount(0, $res);
    }

    /**
     * Tests the __construct() method.
     *
     * @return void
     */
    public function test__construct(): void {

        $obj = $this->documentRepository;

        $this->assertInstanceOf(DocumentRepository::class, $obj);
    }
}
