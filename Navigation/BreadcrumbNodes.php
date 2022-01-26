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

use WBW\Bundle\CoreBundle\Navigation\BreadcrumbNode;
use WBW\Bundle\CoreBundle\Navigation\FontAwesome\BreadcrumbNodeActionEdit;
use WBW\Bundle\CoreBundle\Navigation\FontAwesome\BreadcrumbNodeActionNew;
use WBW\Bundle\CoreBundle\Navigation\NavigationInterface;

/**
 * Breadcrumb nodes.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Navigation
 */
class BreadcrumbNodes {

    /**
     * Get the EDM breadcrumb nodes with Font Awesome icons.
     *
     * @return BreadcrumbNode[] Returns the EDM breadcrumb nodes.
     */
    public static function getFontAwesomeBreadcrumbNodes(): array {

        $nodes = [];

        $nodes[] = new BreadcrumbNodeActionNew("wbw_edm_document_new", NavigationInterface::NAVIGATION_MATCHER_ROUTER);
        $nodes[] = new BreadcrumbNodeActionEdit("wbw_edm_document_edit", NavigationInterface::NAVIGATION_MATCHER_ROUTER);
        $nodes[] = new BreadcrumbNode("label.move", "fa:arrows-alt", "wbw_edm_document_move", NavigationInterface::NAVIGATION_MATCHER_ROUTER);
        $nodes[] = new BreadcrumbNode("label.upload", "fa:upload", "wbw_edm_dropzone_upload", NavigationInterface::NAVIGATION_MATCHER_ROUTER);

        return $nodes;
    }
}
