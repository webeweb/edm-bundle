<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WBW\Bundle\EDMBundle;

/**
 * EDM events.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle
 */
class WBWEDMEvents {

    /**
     * Delete a directory.
     *
     * @string
     */
    const DIRECTORY_DELETE = "wbw.edm.event.delete_directory";

    /**
     * Download a directory.
     *
     * @string
     */
    const DIRECTORY_DOWNLOAD = "wbw.edm.event.download_directory";

    /**
     * Edit a directory.
     *
     * @string
     */
    const DIRECTORY_EDIT = "wbw.edm.event.edit_directory";

    /**
     * Move a directory.
     *
     * @string
     */
    const DIRECTORY_MOVE = "wbw.edm.event.move_directory";

    /**
     * New directory.
     *
     * @string
     */
    const DIRECTORY_NEW = "wbw.edm.event.new_directory";

    /**
     * Open a directory.
     *
     * @string
     */
    const DIRECTORY_OPEN = "wbw.edm.event.open_directory";

    /**
     * Delete a document.
     *
     * @string
     */
    const DOCUMENT_DELETE = "wbw.edm.event.delete_document";

    /**
     * Download a document.
     *
     * @string
     */
    const DOCUMENT_DOWNLOAD = "wbw.edm.event.download_document";

    /**
     * Edit a document.
     *
     * @string
     */
    const DOCUMENT_EDIT = "wbw.edm.event.edit_document";

    /**
     * Move a document.
     *
     * @string
     */
    const DOCUMENT_MOVE = "wbw.edm.event.move_document";

    /**
     * Upload a document.
     *
     * @string
     */
    const DOCUMENT_UPLOAD = "wbw.edm.event.upload_document";

}
