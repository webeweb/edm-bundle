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
     * Document "post delete" event.
     *
     * @var string
     */
    const DOCUMENT_POST_DELETE = "wbw.edm.event.document.post_delete";

    /**
     * Document "post download" event.
     *
     * @var string
     */
    const DOCUMENT_POST_DOWNLOAD = "wbw.edm.event.document.post_download";

    /**
     * Document "post edit" event.
     *
     * @var string
     */
    const DOCUMENT_POST_EDIT = "wbw.edm.event.document.post_edit";

    /**
     * Document "post move" event.
     *
     * @var string
     */
    const DOCUMENT_POST_MOVE = "wbw.edm.event.document.post_move";

    /**
     * Document "post new" event.
     *
     * @var string
     */
    const DOCUMENT_POST_NEW = "wbw.edm.event.document.post_new";

    /**
     * Document "post upload" event.
     *
     * @var string
     */
    const DOCUMENT_POST_UPLOAD = "wbw.edm.event.document.post_upload";

    /**
     * Document "pre delete" event.
     *
     * @var string
     */
    const DOCUMENT_PRE_DELETE = "wbw.edm.event.document.pre_delete";

    /**
     * Document "pre download" event.
     *
     * @var string
     */
    const DOCUMENT_PRE_DOWNLOAD = "wbw.edm.event.document.pre_download";

    /**
     * Document "pre edit" event.
     *
     * @var string
     */
    const DOCUMENT_PRE_EDIT = "wbw.edm.event.document.pre_edit";

    /**
     * Document "pre move" event.
     *
     * @var string
     */
    const DOCUMENT_PRE_MOVE = "wbw.edm.event.document.pre_move";

    /**
     * Document "pre new" event.
     *
     * @var string
     */
    const DOCUMENT_PRE_NEW = "wbw.edm.event.document.pre_new";

    /**
     * Document "pre upload" event.
     *
     * @var string
     */
    const DOCUMENT_PRE_UPLOAD = "wbw.edm.event.document.pre_upload";
}
