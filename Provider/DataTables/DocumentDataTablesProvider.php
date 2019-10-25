<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider\DataTables;

use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\JQuery\DataTablesBundle\API\DataTablesColumnInterface;
use WBW\Bundle\JQuery\DataTablesBundle\Provider\AbstractDataTablesProvider;

/**
 * Document DataTables provider.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider\DataTables
 */
class DocumentDataTablesProvider extends AbstractDataTablesProvider {

    /**
     * DataTables name.
     *
     * @var string
     */
    const DATATABLES_NAME = "wbw-edm-document";

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "wbw.edm.provider.datatables.document";

    /**
     * {@inheritDoc}
     */
    public function getColumns() {

        /** @var DataTablesColumnInterface[] $dtColumns */
        $dtColumns = [];

        return $dtColumns;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntity() {
        return Document::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getName() {
        return self::DATATABLES_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrefix() {
        return "d";
    }

    /**
     * {@inheritDoc}
     */
    public function getView() {
        return "@WBWEDM/Document/index/content.html.twig";
    }

    /**
     * {@inheritDoc}
     */
    public function renderColumn(DataTablesColumnInterface $dtColumn, $entity) {

        $output = null;

        switch ($dtColumn->getData()) {

            case "actions":

                break;
        }

        return $output;
    }

    /**
     * {@inheritDoc}
     */
    public function renderRow($dtRow, $entity, $rowNumber) {
        return null;
    }
}
