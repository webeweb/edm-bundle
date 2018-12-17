<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Controller;

use DateTime;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Event\DocumentEvents;
use WBW\Bundle\EDMBundle\Form\Type\Document\MoveDocumentType;
use WBW\Bundle\EDMBundle\Form\Type\Document\NewDocumentType;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentType;
use WBW\Bundle\EDMBundle\Helper\DocumentHelper;
use WBW\Bundle\EDMBundle\Manager\StorageManagerInterface;
use WBW\Library\Core\Sorting\AlphabeticalTreeSort;

/**
 * Document controller.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 */
class DocumentController extends AbstractEDMController {

    /**
     * Deletes a directory entity.
     *
     * @param Document $document The document entity.
     * @return Response Returns the response.
     */
    public function deleteAction(Document $document) {

        // Determines the type.
        if (true === $document->isDirectory()) {
            $event = new DocumentEvent(DocumentEvents::DIRECTORY_DELETE, clone $document);
            $type  = "directory";
        } else {
            $event = new DocumentEvent(DocumentEvents::DOCUMENT_DELETE, clone $document);
            $type  = "document";
        }

        try {

            // Get the entities manager and delete the entity.
            $em = $this->getDoctrine()->getManager();
            $em->remove($document);
            $em->flush();

            // Dispatch the event.
            if ($this->getEventDispatcher()->hasListeners($event->getEventName())) {
                $this->getEventDispatcher()->dispatch($event->getEventName(), $event);
            }

            // Notify the user.
            $this->notifySuccess($this->getNotification("DocumentController.deleteAction.success." . $type));
        } catch (ForeignKeyConstraintViolationException $ex) {

            // Notify the user.
            $this->notifyDanger($this->getNotification("DocumentController.deleteAction.danger." . $type));
        }

        // Return the response.
        return $this->redirectToRoute("edm_directory_open", [
                "id" => null === $document->getParent() ? null : $document->getParent()->getId(),
        ]);
    }

    /**
     * Download an existing document entity.
     *
     * @param Document $document The document entity.
     * @return Response Returns the response.
     */
    public function downloadAction(Document $document) {

        // Get the storage manager.
        $storage = $this->get(StorageManagerInterface::SERVICE_NAME);

        // Download the file
        $current = $storage->downloadDocument($document);

        // Dispatch the event.
        if ($this->getEventDispatcher()->hasListeners(DocumentEvents::DOCUMENT_DOWNLOAD)) {
            $this->getEventDispatcher()->dispatch(DocumentEvents::DOCUMENT_DOWNLOAD, new DocumentEvent(DocumentEvents::DOCUMENT_DOWNLOAD, $document));
        }

        // Initialize the response.
        $response = new Response();
        $response->headers->set("Cache-Control", "private");
        $response->headers->set("Content-Type", $current->getMimeType());
        $response->headers->set("Content-Disposition", "attachment; filename=\"" . DocumentHelper::getFilename($current) . "\";");
        $response->headers->set("Content-Length", $current->getSize());

        // Send the headers.
        $response->sendHeaders();

        // Set the content.
        $response->setContent($storage->readDocument($current));

        // Return the response.
        return $response;
    }

    /**
     * Displays a form to edit an existing document entity.
     *
     * @param Request $request The request.
     * @param Document $document The document entity.
     * @return Response Returns the response.
     */
    public function editAction(Request $request, Document $document) {

        // Determines the type.
        if (true === $document->isDirectory()) {
            $event = new DocumentEvent(DocumentEvents::DIRECTORY_EDIT, $document);
            $type  = "directory";
        } else {
            $event = new DocumentEvent(DocumentEvents::DOCUMENT_EDIT, $document);
            $type  = "document";
        }

        // Create the form.
        $form = $this->createForm(NewDocumentType::class, $document);

        // Handle the request and check if the form is submitted and valid.
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Set the updated at.
            $document->setUpdatedAt(new DateTime());

            // Get the entities manager and update the entity.
            $this->getDoctrine()->getManager()->flush();

            // Dispatch the event.
            if ($this->getEventDispatcher()->hasListeners($event->getEventName())) {
                $this->getEventDispatcher()->dispatch($event->getEventName(), $event);
            }

            // Notify the user.
            $this->notifySuccess($this->getNotification("DocumentController.moveAction.success." . $type));

            // Return the response.
            return $this->redirectToRoute("edm_directory_open", [
                    "id" => null === $document->getParent() ? null : $document->getParent()->getId(),
            ]);
        }

        // Return the response.
        return $this->render("@EDM/Document/new.html.twig", [
                "form"     => $form->createView(),
                "document" => $document,
                "location" => $document
        ]);
    }

    /**
     * Displays a form to move an existing document entity.
     *
     * @param Request $request The request.
     * @param Document $document The document entity.
     * @return Response Returns the response.
     */
    public function moveAction(Request $request, Document $document) {

        // Determines the type.
        if (true === $document->isDirectory()) {
            $event  = new DocumentEvent(DocumentEvents::DIRECTORY_MOVE, $document);
            $except = $document;
            $type   = "directory";
        } else {
            $event  = new DocumentEvent(DocumentEvents::DOCUMENT_MOVE, $document);
            $except = $document->getParent();
            $type   = "document";
        }

        // Get the entities manager.
        $em = $this->getDoctrine()->getManager();

        // Find the entities.
        $directories = $em->getRepository(Document::class)->findAllDirectoriesExcept($except);

        // Create the form.
        $form = $this->createForm(MoveDocumentType::class, $document, [
            "entity.parent" => $directories
        ]);

        // Handle the request and check if the form is submitted and valid.
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Set the updated at.
            $document->setUpdatedAt(new DateTime());

            // Get the entities manager and update the entity.
            $this->getDoctrine()->getManager()->flush();

            // Dispatch the event.
            if ($this->getEventDispatcher()->hasListeners($event->getEventName())) {
                $this->getEventDispatcher()->dispatch($event->getEventName(), $event);
            }

            // Notify the user.
            $this->notifySuccess($this->getNotification("DocumentController.editAction.success." . $type));

            // Return the response.
            return $this->redirectToRoute("edm_directory_open", [
                    "id" => null === $document->getParent() ? null : $document->getParent()->getId(),
            ]);
        }

        // Return the response.
        return $this->render("@EDM/Document/move.html.twig", [
                "form"     => $form->createView(),
                "document" => $document,
                "location" => $document
        ]);
    }

    /**
     * Creates a new directory entity.
     *
     * @param Request $request The request.
     * @param Document $parent The directory entity.
     * @return Response Returns the response.
     */
    public function newAction(Request $request, Document $parent = null) {

        // Create the entity.
        $directory = new Document();
        $directory->setParent($parent);
        $directory->setSize(0);
        $directory->setType(Document::TYPE_DIRECTORY);

        // Create the form.
        $form = $this->createForm(NewDocumentType::class, $directory);

        // Handle the request and check if the form is submitted and valid.
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Set the created at.
            $directory->setCreatedAt(new DateTime());

            // Get the entities manager and insert the entity.
            $em = $this->getDoctrine()->getManager();
            $em->persist($directory);
            $em->flush();

            // Dispatch the event.
            if ($this->getEventDispatcher()->hasListeners(DocumentEvents::DIRECTORY_NEW)) {
                $this->getEventDispatcher()->dispatch(DocumentEvents::DIRECTORY_NEW, new DocumentEvent(DocumentEvents::DIRECTORY_NEW, $directory));
            }

            // Notity the user.
            $this->notifySuccess($this->getNotification("DocumentController.newAction.success.directory"));

            // Return the response.
            return $this->redirectToRoute("edm_directory_open", [
                    "id" => null === $parent ? null : $parent->getId(),
            ]);
        }

        // Return the response.
        return $this->render("@EDM/Document/new.html.twig", [
                "form"     => $form->createView(),
                "document" => $directory,
                "location" => $parent,
        ]);
    }

    /**
     * Open an existing document entity.
     *
     * @param Document $directory The document entity.
     * @return Response Returns the response.
     */
    public function openAction(Document $directory = null) {

        // Get the entities manager.
        $em = $this->getDoctrine()->getManager();

        // Find the entities.
        $directories = $em->getRepository(Document::class)->findAllByParent($directory);

        // Dispatch the event.
        if ($this->getEventDispatcher()->hasListeners(DocumentEvents::DIRECTORY_OPEN) && null !== $directory) {
            $this->getEventDispatcher()->dispatch(DocumentEvents::DIRECTORY_OPEN, new DocumentEvent(DocumentEvents::DIRECTORY_OPEN, $directory));
        }

        // Check the documents.
        if (0 === count($directories)) {

            // Notify the user.
            $this->notifySuccess($this->getNotification("DocumentController.openAction.info"));
        }

        // Initialize the sorter.
        $sorter = new AlphabeticalTreeSort($directories);
        $sorter->sort();

        // Return the response.
        return $this->render("@EDM/Document/open.html.twig", [
                "documents" => $sorter->getNodes(),
                "directory" => $directory
        ]);
    }

    /**
     * Upload a document entity.
     *
     * @param Request $request The request.
     * @param Document $parent The document entity.
     * @return Response Returns the response.
     */
    public function uploadAction(Request $request, Document $parent = null) {

        // Create the entity.
        $document = new Document();
        $document->setParent($parent);
        $document->setSize(0);
        $document->setType(Document::TYPE_DOCUMENT);

        // Create the form.
        $form = $this->createForm(UploadDocumentType::class, $document);

        // Handle the request and check if the form is submitted and valid.
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // Set the created at.
            $document->setCreatedAt(new DateTime());

            // Get the entities manager and insert the entity.
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            // Dispatch the event.
            if ($this->getEventDispatcher()->hasListeners(DocumentEvents::DOCUMENT_UPLOAD)) {
                $this->getEventDispatcher()->dispatch(DocumentEvents::DOCUMENT_UPLOAD, new DocumentEvent(DocumentEvents::DOCUMENT_UPLOAD, $document));
            }

            // Notity the user.
            $this->notifySuccess($this->getNotification("DocumentController.uploadAction.success"));

            // Return the response.
            return $this->redirectToRoute("edm_directory_open", [
                    "id" => is_null($parent) ? null : $parent->getId(),
            ]);
        }

        // Return the response.
        return $this->render("@EDM/Document/upload.html.twig", [
                "form"     => $form->createView(),
                "document" => $document,
        ]);
    }

}
