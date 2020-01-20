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
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\Document\MoveDocumentFormType;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentFormType;
use WBW\Bundle\EDMBundle\Form\Type\DocumentFormType;
use WBW\Bundle\EDMBundle\Provider\DataTables\DocumentDataTablesProvider;
use WBW\Bundle\EDMBundle\Repository\DocumentRepository;
use WBW\Bundle\EDMBundle\WBWEDMEvents;

/**
 * Document controller.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 */
class DocumentController extends AbstractController {

    /**
     * Deletes an existing document.
     *
     * @param Document $document The document.
     * @return Response Returns the response.
     */
    public function deleteAction(Document $document) {

        $type = $document->isDocument() ? "document" : "directory";

        try {

            // Clone to preserve id attribute.
            $backedUp = clone $document;

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_PRE_DELETE, $document);

            $em = $this->getDoctrine()->getManager();
            $em->remove($document);
            $em->flush();

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_POST_DELETE, $backedUp);

            $this->notifySuccess($this->translate("DocumentController.deleteAction.success.${type}"));
        } catch (ForeignKeyConstraintViolationException $ex) {

            $this->notifyDanger($this->translate("DocumentController.deleteAction.danger.${type}"));
        }

        list($route, $parameters) = $this->buildRedirectRoute($document);
        return $this->redirectToRoute($route, $parameters);
    }

    /**
     * Download an existing document.
     *
     * @param Document $document The document.
     * @return Response Returns the response.
     */
    public function downloadAction(Document $document) {

        $event = $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_PRE_DOWNLOAD, $document);
        if (null === $event->getResponse()) {
            return new Response("Internal Server Error", 500);
        }

        return $event->getResponse();
    }

    /**
     * Displays a form to edit an existing document.
     *
     * @param Request $request The request.
     * @param Document $document The document.
     * @return Response Returns the response.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function editAction(Request $request, Document $document) {

        $type = $document->isDocument() ? "document" : "directory";

        $form = $this->createForm(DocumentFormType::class, $document);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_PRE_EDIT, $document);

            $document->setUpdatedAt(new DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_POST_EDIT, $document);

            $this->notifySuccess($this->translate("DocumentController.editAction.success.${type}"));

            list($route, $parameters) = $this->buildRedirectRoute($document);
            return $this->redirectToRoute($route, $parameters);
        }

        return $this->render("@WBWEDM/Document/form.html.twig", [
            "form"     => $form->createView(),
            "document" => $document,
        ]);
    }

    /**
     * Index all documents.
     *
     * @param Request $request The request.
     * @return Response Returns the response.
     */
    public function indexAction(Request $request) {

        $path  = ["name" => DocumentDataTablesProvider::DATATABLES_NAME];
        $query = $request->query->all();

        return $this->forward("WBWJQueryDataTablesBundle:DataTables:index", $path, $query);
    }

    /**
     * Displays a form to move an existing document.
     *
     * @param Request $request The request.
     * @param Document $document The document.
     * @return Response Returns the response.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function moveAction(Request $request, Document $document) {

        $except = $document->isDirectory() ? $document : $document->getParent();
        $type   = $document->isDocument() ? "document" : "directory";

        /** @var DocumentRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Document::class);

        $form = $this->createForm(MoveDocumentFormType::class, $document, [
            "entity.parent" => $repository->findAllDirectoriesExcept($except),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_PRE_MOVE, $document);

            $document->setUpdatedAt(new DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_POST_MOVE, $document);

            $this->notifySuccess($this->translate("DocumentController.moveAction.success.${type}"));

            list($route, $parameters) = $this->buildRedirectRoute($document);
            return $this->redirectToRoute($route, $parameters);
        }

        return $this->render("@WBWEDM/Document/move.html.twig", [
            "form"     => $form->createView(),
            "document" => $document,
        ]);
    }

    /**
     * Creates a new document.
     *
     * @param Request $request The request.
     * @param Document $parent The parent.
     * @return Response Returns the response.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function newAction(Request $request, Document $parent = null) {

        $document = new Document();
        $document->setCreatedAt(new DateTime());
        $document->setParent($parent);
        $document->setSize(0);
        $document->setType(Document::TYPE_DIRECTORY);

        $form = $this->createForm(DocumentFormType::class, $document);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_PRE_NEW, $document);

            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_POST_NEW, $document);

            $this->notifySuccess($this->translate("DocumentController.newAction.success.directory"));

            list($route, $parameters) = $this->buildRedirectRoute($document);
            return $this->redirectToRoute($route, $parameters);
        }

        return $this->render("@WBWEDM/Document/form.html.twig", [
            "form"     => $form->createView(),
            "document" => $document,
        ]);
    }

    /**
     * Upload a document entity.
     *
     * @param Request $request The request.
     * @param Document $parent The document entity.
     * @return Response Returns the response.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function uploadAction(Request $request, Document $parent = null) {

        $document = new Document();
        $document->setCreatedAt(new DateTime());
        $document->setParent($parent);
        $document->setSize(0);
        $document->setType(Document::TYPE_DOCUMENT);

        $form = $this->createForm(UploadDocumentFormType::class, $document);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_PRE_NEW, $document);

            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            $this->dispatchDocumentEvent(WBWEDMEvents::DOCUMENT_POST_NEW, $document);

            $this->notifySuccess($this->translate("DocumentController.uploadAction.success.document"));

            list($route, $parameters) = $this->buildRedirectRoute($document);
            return $this->redirectToRoute($route, $parameters);
        }

        return $this->render("@WBWEDM/Document/upload.html.twig", [
            "form"     => $form->createView(),
            "document" => $document,
        ]);
    }
}
