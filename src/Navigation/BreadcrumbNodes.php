<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2020 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Navigation;

use WBW\Library\Widget\Component\Navigation\BreadcrumbNode;
use WBW\Library\Widget\Component\NavigationNodeInterface;

/**
 * Breadcrumb nodes.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Navigation
 */
class BreadcrumbNodes {

    /**
     * Get the EDM breadcrumb nodes with Font Awesome icons.
     *
     * @return BreadcrumbNode[] Returns the EDM breadcrumb nodes.
     */
    public static function getFontAwesomeBreadcrumbNodes(): array {

        return [
            new BreadcrumbNode("label.new", "fa:plus", "wbw_edm_document_new", NavigationNodeInterface::MATCHER_ROUTER),
            new BreadcrumbNode("label.edit", "fa:pen", "wbw_edm_document_edit", NavigationNodeInterface::MATCHER_ROUTER),
            new BreadcrumbNode("label.move", "fa:arrows-alt", "wbw_edm_document_move", NavigationNodeInterface::MATCHER_ROUTER),
            new BreadcrumbNode("label.upload", "fa:upload", "wbw_edm_dropzone_upload", NavigationNodeInterface::MATCHER_ROUTER),
        ];
    }
}
