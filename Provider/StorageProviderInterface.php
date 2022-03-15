<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Provider;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Library\Symfony\Provider\ProviderInterface;

/**
 * Storage provider interface.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Provider
 */
interface StorageProviderInterface extends ProviderInterface {

    /**
     * Tag name.
     *
     * @var string
     */
    const TAG_NAME = "wbw.edm.provider.storage";

    /**
     * Delete a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function deleteDirectory(DocumentInterface $directory): void;

    /**
     * Delete a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function deleteDocument(DocumentInterface $document): void;

    /**
     * Download a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return Response Returns the response.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function downloadDirectory(DocumentInterface $directory): Response;

    /**
     * Download a document.
     *
     * @param DocumentInterface $document The document.
     * @return Response Returns the response.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function downloadDocument(DocumentInterface $document): Response;

    /**
     * Move a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     */
    public function moveDocument(DocumentInterface $document): void;

    /**
     * Create a directory.
     *
     * @param DocumentInterface $directory The directory.
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function newDirectory(DocumentInterface $directory): void;

    /**
     * Upload a document.
     *
     * @param DocumentInterface $document The document.
     * @return void
     * @throws Exception Throws an exception if an error occurs.
     */
    public function uploadDocument(DocumentInterface $document): void;
}
