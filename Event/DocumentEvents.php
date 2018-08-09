<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Event;

/**
 * Document events.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Event
 */
class DocumentEvents {

    /**
     * Directory delete action.
     *
     * @string
     */
    const DIRECTORY_DELETE = "webeweb.edm.event.directory.delete";

    /**
     * Directory download action.
     *
     * @string
     */
    const DIRECTORY_DOWNLOAD = "webeweb.edm.event.directory.download";

    /**
     * Directory edit action.
     *
     * @string
     */
    const DIRECTORY_EDIT = "webeweb.edm.event.directory.edit";

    /**
     * Directory move action.
     *
     * @string
     */
    const DIRECTORY_MOVE = "webeweb.edm.event.directory.move";

    /**
     * Directory new action.
     *
     * @string
     */
    const DIRECTORY_NEW = "webeweb.edm.event.directory.new";

    /**
     * Directory open action.
     *
     * @string
     */
    const DIRECTORY_OPEN = "webeweb.edm.event.directory.open";

    /**
     * Document delete action.
     *
     * @string
     */
    const DOCUMENT_DELETE = "webeweb.edm.event.document.delete";

    /**
     * Document download action.
     *
     * @string
     */
    const DOCUMENT_DOWNLOAD = "webeweb.edm.event.document.download";

    /**
     * Document edit action.
     *
     * @string
     */
    const DOCUMENT_EDIT = "webeweb.edm.event.document.edit";

    /**
     * Document move action.
     *
     * @string
     */
    const DOCUMENT_MOVE = "webeweb.edm.event.document.move";

    /**
     * Document upload action.
     *
     * @string
     */
    const DOCUMENT_UPLOAD = "webeweb.edm.event.document.upload";

}
