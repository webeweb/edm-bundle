<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;

/**
 * Document repository.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Repository
 */
class DocumentRepository extends EntityRepository {

    /**
     * {@inheritDoc}
     */
    public function find($id, $lockMode = null, $lockVersion = null) {

        $qb = $this->createQueryBuilder("d");
        $qb->leftJoin("d.parent", "p")
            ->addSelect("p")
            ->leftJoin("d.children", "c")
            ->addSelect("c")
            ->andWhere("d.id = :id")
            ->setParameter(":id", $id)
            ->orderBy("d.name", "ASC");

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Find all by parent.
     *
     * @param DocumentInterface $parent The parent.
     * @return DocumentInterface[] Returns the documents.
     */
    public function findAllByParent(DocumentInterface $parent = null) {

        $qb = $this->createQueryBuilder("d");
        $qb->leftJoin("d.parent", "p")
            ->addSelect("p")
            ->leftJoin("d.children", "c")
            ->addSelect("c")
            ->orderBy("d.name", "ASC");

        if (null !== $parent) {
            $qb->andWhere("d.parent = :parent")
                ->setParameter(":parent", $parent);
        } else {
            $qb->andWhere("d.parent IS NULL");
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find all directories.
     *
     * @param DocumentInterface $exclude The excluded directory.
     * @return DocumentInterface[] Returns the directories.
     */
    public function findAllDirectoriesExcept(DocumentInterface $exclude = null) {

        $qb = $this->createQueryBuilder("d");
        $qb->leftJoin("d.parent", "p")
            ->addSelect("p")
            ->leftJoin("d.children", "c")
            ->addSelect("c")
            ->andWhere("d.type = :type")
            ->setParameter(":type", Document::TYPE_DIRECTORY)
            ->orderBy("d.name", "ASC");

        if (null !== $exclude) {
            $qb->andWhere("d.id <> :id")
                ->setParameter("id", $exclude);
        }

        return $this->removeOrphans($qb->getQuery()->getResult());
    }

    /**
     * Find all documents by parent.
     *
     * @param Document $parent The directory.
     * @return Document[] Returns the document.
     */
    public function findAllDocumentsByParent(Document $parent = null) {

        $qb = $this->createQueryBuilder("d");
        $qb->leftJoin("d.parent", "p")
            ->addSelect("p")
            ->leftJoin("d.children", "c")
            ->addSelect("c")
            ->andWhere("d.type = :type")
            ->setParameter(":type", Document::TYPE_DOCUMENT)
            ->orderBy("d.name", "ASC");

        if (null === $parent) {
            $qb->andWhere("d.parent IS NULL");
        } else {
            $qb->andWhere("d.parent = :parent")
                ->setParameter("parent", $parent);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Remove orphans.
     *
     * @param DocumentInterface[] $documents The documents.
     * @return DocumentInterface[] Returns the documents.
     */
    private function removeOrphans(array $documents) {

        $keys = [];

        foreach ($documents as $current) {
            $keys[] = $current->getId();
        }

        foreach ($documents as $k => $v) {
            if (null === $v->getParent() || true === in_array($v->getParent()->getId(), $keys)) {
                continue;
            }
            unset($documents[$k]);
        }

        return $documents;
    }
}
