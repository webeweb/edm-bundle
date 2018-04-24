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

/**
 * Document repository.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Repository
 */
class DocumentRepository extends EntityRepository {

    /**
     * Find all document by parent.
     *
     * @param Document $parent The directory.
     * @return Document[] Returns the document.
     */
    public function findAllByParent(Document $parent = null) {

        // Create a query builder.
        $qb = $this->createQueryBuilder("d");

        // Initialize the query builder.
        $qb->leftJoin("d.parent", "p")
            ->addSelect("p")
            ->leftJoin("d.childrens", "c")
            ->addSelect("c")
            ->orderBy("d.name", "ASC");

        // Check the parent.
        if (null === $parent) {
            $qb->andWhere("d.parent IS NULL");
        } else {
            $qb->andWhere("d.parent = :parent")
                ->setParameter("parent", $parent);
        }

        // Return the query result.
        return $qb->getQuery()->getResult();
    }

    /**
     * Find all directories.
     *
     * @param Document $exclude The excluded directory.
     * @return Document[] Returns the document.
     */
    public function findAllDirectoriesExcept(Document $exclude = null) {

        // Create a query builder.
        $qb = $this->createQueryBuilder("d");

        // Initialize the query builder.
        $qb->leftJoin("d.parent", "p")
            ->addSelect("p")
            ->leftJoin("d.childrens", "c")
            ->addSelect("c")
            ->where("d.type = :type")
            ->setParameter("type", Document::TYPE_DIRECTORY)
            ->orderBy("d.name", "ASC");

        // Check the parent.
        if (null !== $exclude) {
            $qb
                ->andWhere("d.id <> :id")
                ->setParameter("id", $exclude);
        }

        // Return the query result.
        return $this->removeOrphans($qb->getQuery()->getResult());
    }

    /**
     * Find all document by parent.
     *
     * @param Document $parent The directory.
     * @return Document[] Returns the document.
     */
    public function findAllDocumentsByParent(Document $parent = null) {

        // Create a query builder.
        $qb = $this->createQueryBuilder("d");

        // Initialize the query builder.
        $qb->leftJoin("d.parent", "p")
            ->addSelect("p")
            ->leftJoin("d.childrens", "c")
            ->addSelect("c")
            ->where("d.type = :type")
            ->setParameter("type", Document::TYPE_DOCUMENT)
            ->orderBy("d.name", "ASC");

        // Check the parent.
        if (null === $parent) {
            $qb->andWhere("d.parent IS NULL");
        } else {
            $qb->andWhere("d.parent = :parent")
                ->setParameter("parent", $parent);
        }

        // Return the query result.
        return $qb->getQuery()->getResult();
    }

    /**
     * Remove orphans.
     *
     * @param Document[] $documents The document entities.
     * @return Document[] Returns the document entities.
     */
    private function removeOrphans(array $documents) {

        // Initialize the keys.
        $keys = [];

        // Handle each document.
        foreach ($documents as $current) {
            $keys[] = $current->getId();
        }
        foreach ($documents as $k => $v) {
            if (null !== $v->getParent() && false === in_array($v->getParent()->getId(), $keys)) {
                unset($documents[$k]);
            }
        }

        // Return
        return $documents;
    }

}
