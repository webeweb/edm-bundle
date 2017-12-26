<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
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
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Repository
 */
final class DocumentRepository extends EntityRepository {

	/**
	 * Find all.
	 *
	 * @param Document $parent The directory.
	 * @return Doucment[] Returns the document.
	 */
	public function findAllByParent(Document $parent = null) {

		// Create a query builder.
		$qb = $this->createQueryBuilder("d");

		// Initialize the query builder.
		$qb
			->leftJoin("d.parent", "p")
			->addSelect("p")
			->leftJoin("d.childrens", "c")
			->addSelect("c")
			->orderBy("d.name", "ASC");

		// Check the parent.
		if (is_null($parent)) {
			$qb->andWhere("d.parent IS NULL");
		} else {
			$qb
				->andWhere("d.parent = :parent")
				->setParameter("parent", $parent);
		}

		// Return the query result.
		return $qb->getQuery()->getResult();
	}

}
