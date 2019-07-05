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
     * Directory delete.
     *
     * @string
     */
    const DIRECTORY_DELETE = "wbw.edm.event.delete_directory";

    /**
     * Directory download.
     *
     * @string
     */
    const DIRECTORY_DOWNLOAD = "wbw.edm.event.download_directory";

    /**
     * Directory edit.
     *
     * @string
     */
    const DIRECTORY_EDIT = "wbw.edm.event.edit_directory";

    /**
     * Directory move.
     *
     * @string
     */
    const DIRECTORY_MOVE = "wbw.edm.event.move_directory";

    /**
     * Directory new.
     *
     * @string
     */
    const DIRECTORY_NEW = "wbw.edm.event.new_directory";

    /**
     * Directory open.
     *
     * @string
     */
    const DIRECTORY_OPEN = "wbw.edm.event.open_directory";

    /**
     * Document delete.
     *
     * @string
     */
    const DOCUMENT_DELETE = "wbw.edm.event.delete_document";

    /**
     * Document download.
     *
     * @string
     */
    const DOCUMENT_DOWNLOAD = "wbw.edm.event.download_document";

    /**
     * Document edit.
     *
     * @string
     */
    const DOCUMENT_EDIT = "wbw.edm.event.edit_document";

    /**
     * Document move.
     *
     * @string
     */
    const DOCUMENT_MOVE = "wbw.edm.event.move_document";

    /**
     * Document upload.
     *
     * @string
     */
    const DOCUMENT_UPLOAD = "wbw.edm.event.upload_document";

}
