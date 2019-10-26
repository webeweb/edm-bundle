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

use WBW\Bundle\CoreBundle\Renderer\FileSizeRenderer;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\JQuery\DataTablesBundle\API\DataTablesColumnInterface;
use WBW\Bundle\JQuery\DataTablesBundle\Factory\DataTablesFactory;
use WBW\Bundle\JQuery\DataTablesBundle\Provider\AbstractDataTablesProvider;
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
        return $this->getRouter()->generate("wbw_edm_directory_index");
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

        switch ($dtColumn->getData()) {

            case "actions":
                $output = null;
                break;

            case "name":
                $output = DocumentHelper::getFilename($entity);
                break;

            case "size":
                $output = $this->renderColumnSize($entity);
                break;

            case "type":
                $output = "";
                break;
        }

        return $output;
    }

    /**
     * Render a column "size".
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the rendered column "size".
     */
    protected function renderColumnSize(DocumentInterface $document) {

        $output = [
            FileSizeRenderer::renderSize($document->getSize()),
        ];

        if ($document->isDirectory()) {
            $output[] = $this->translate("label.items_count", ["{{ count }}" => count($document->getChildren())]);
        }

        return implode("<br/>", $output);
    }

    /**
     * {@inheritDoc}
     */
    public function renderRow($dtRow, $entity, $rowNumber) {
        return null;
    }

    /**
     * Translate.
     *
     * @param string $id The id.
     * @param array $parameters The parameters.
     * @return string Returns the translation.
     */
    protected function translate($id, array $parameters = []) {
        return $this->getTranslator()->trans($id, $parameters, "WBWEDMBundle");
    }
}
