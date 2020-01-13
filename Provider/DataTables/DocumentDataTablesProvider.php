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
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\JQuery\DataTablesBundle\API\DataTablesColumnInterface;
use WBW\Bundle\JQuery\DataTablesBundle\Factory\DataTablesFactory;
use WBW\Bundle\JQuery\DataTablesBundle\Provider\DataTablesRouterInterface;

/**
 * Document DataTables provider.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Provider\DataTables
 */
class DocumentDataTablesProvider extends AbstractDataTablesProvider implements DataTablesRouterInterface {

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

        $dtColumns[] = DataTablesFactory::newColumn("name", $this->translate("label.name"));
        $dtColumns[] = DataTablesFactory::newColumn("size", $this->translate("label.size"))
            ->setWidth("111px");
        $dtColumns[] = DataTablesFactory::newColumn("updatedAt", $this->translate("label.updated_at"))
            ->setWidth("111px");
        $dtColumns[] = DataTablesFactory::newColumn("type", $this->translate("label.type"))
            ->setWidth("185px")
            ->setSearchable(false);
        $dtColumns[] = DataTablesFactory::newColumn("actions", $this->translate("label.actions"))
            ->setWidth("185px")
            ->setOrderable(false)
            ->setSearchable(false);

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
    public function getOptions() {

        $dtOptions = parent::getOptions();

        $dtOptions->addOption("bPaginate", false);
        $dtOptions->addOption("order", [[4, "desc"], [1, "asc"]]);

        return $dtOptions;
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
    public function getUrl() {
        return $this->getRouter()->generate("wbw_edm_document_index");
    }

    /**
     * {@inheritDoc}
     */
    public function getView() {
        return "@WBWEDM/Document/index.html.twig";
    }

    /**
     * {@inheritDoc}
     */
    public function renderColumn(DataTablesColumnInterface $dtColumn, $entity) {

        $output = null;

        /** @var DocumentInterface $entity */
        switch ($dtColumn->getData()) {

            case "actions":
                $output = $this->renderColumnActions($entity);
                break;

            case "name":
                $output = $this->renderColumnName($entity);
                break;

            case "size":
                $output = $this->renderColumnSize($entity);
                break;

            case "type":
                $output = $this->renderColumnType($entity);
                break;

            case "updatedAt":
                $output = $this->renderColumnUpdatedAt($entity);
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
