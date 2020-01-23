<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Helper;

use InvalidArgumentException;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;

/**
 * Document helper.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Helper
 */
class DocumentHelper {

    /**
     * Flatten the children.
     *
     * @param DocumentInterface $document The document.
     * @return DocumentInterface[] Returns the flatten children.
     */
    public static function flattenChildren(DocumentInterface $document) {

        $children = [];

        foreach ($document->getChildren() as $current) {
            $children = array_merge($children, [$current], static::flattenChildren($current));
        }

        return $children;
    }

    /**
     * Get a filename.
     *
     * @param DocumentInterface $document The document.
     * @return string Returns the filename.
     */
    public static function getFilename(DocumentInterface $document) {
        if ($document->isDirectory()) {
            return $document->getName();
        }
        $filename = implode(".", [$document->getName(), $document->getExtension()]);
        return "." !== $filename ? $filename : "";
    }

    /**
     * Get a pathname.
     *
     * @param DocumentInterface $document The document.
     * @return string Return the pathname.
     */
    public static function getPathname(DocumentInterface $document) {

        $path = [];
        foreach (static::getPaths($document, false) as $current) {
            $path[] = static::getFilename($current);
        }

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Get the paths.
     *
     * @param DocumentInterface $document The document.
     * @param bool $backedUp Backed up ?
     * @return DocumentInterface[] Returns the paths.
     */
    public static function getPaths(DocumentInterface $document, $backedUp = false) {

        $path = [];

        $current = $document;
        while (null !== $current) {
            array_unshift($path, $current); // Insert parent path at start.
            $current = $current === $document && true === $backedUp ? $current->getSavedParent() : $current->getParent();
        }

        return $path;
    }

    /**
     * Determines if a document is a directory.
     *
     * @param DocumentInterface $document The document.
     * @return bool Returns true.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is of 'document' type.
     */
    public static function isDirectory(DocumentInterface $document) {
        if (false === $document->isDirectory()) {
            throw new InvalidArgumentException("The document must be of 'directory' type");
        }
        return true;
    }

    /**
     * Determines if a document is a document.
     *
     * @param DocumentInterface $document The document.
     * @return bool Returns true.
     * @throws InvalidArgumentException Throws an invalid argument exception if the document is of 'directory' type.
     */
    public static function isDocument(DocumentInterface $document) {
        if (false === $document->isDocument()) {
            throw new InvalidArgumentException("The document must be of 'document' type");
        }
        return true;
    }

    /**
     * Normalize a document.
     *
     * @param DocumentInterface $document The document.
     * @return array Returns a normalized document
     */
    public static function normalize(DocumentInterface $document) {

        $children = [];
        $parent   = null;

        /** @var DocumentInterface $current */
        foreach ($document->getChildren() as $current) {
            $children[] = $current->getId();
        }

        if (null !== $document->getParent()) {
            $parent = static::normalize($document->getParent());
        }

        return [
            "id"              => $document->getId(),
            "children"        => $children,
            "createdAt"       => $document->getCreatedAt(),
            "extension"       => $document->getExtension(),
            "filename"        => static::getFilename($document),
            "hash"            => $document->getHash(),
            "mimeType"        => $document->getMimeType(),
            "name"            => $document->getName(),
            "numberDownloads" => $document->getNumberDownloads(),
            "parent"          => $parent,
            "size"            => $document->getSize(),
            "type"            => $document->getType(),
            "updatedAt"       => $document->getUpdatedAt(),
        ];
    }
}
