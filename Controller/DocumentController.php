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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WBW\Bundle\EDMBundle\DataTables\Provider\DocumentDataTablesProvider;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Form\Type\Document\MoveDocumentFormType;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentFormType;
use WBW\Bundle\EDMBundle\Form\Type\DocumentFormType;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Repository\DocumentRepository;
use WBW\Bundle\JQuery\DataTablesBundle\Controller\DataTablesController;

/**
 * Document controller.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Controller
 */
class DocumentController extends AbstractController {

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "wbw.edm.controller.document";

    /**
     * Deletes an existing document.
     *
     * @param int $id The document.
     * @return Response Returns the response.
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function deleteAction(int $id): Response {

        $document = $this->findDocument($id, true);

        $type = $document->isDocument() ? "document" : "directory";

        try {

            $backedUp = clone $document; // Clone to preserve id attribute

            $this->dispatchDocumentEvent(DocumentEvent::PRE_DELETE, $document);

            $em = $this->getEntityManager();
            $em->remove($document);
            $em->flush();

            $this->dispatchDocumentEvent(DocumentEvent::POST_DELETE, $backedUp);

            $this->notifySuccess($this->translate("DocumentController.deleteAction.success.$type"));
        } catch (Throwable $ex) {

            $this->notifyDanger($this->translate("DocumentController.deleteAction.danger.$type"));
        }

        [$route, $parameters] = $this->buildRedirectRoute($document);

        return $this->redirectToRoute($route, $parameters);
    }

    /**
     * Downloads an existing document.
     *
     * @param int $id The document.
     * @return Response Returns the response.
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function downloadAction(int $id): Response {

        $document = $this->findDocument($id, true);

        $event = $this->dispatchDocumentEvent(DocumentEvent::PRE_DOWNLOAD, $document);
        if (null === $event->getResponse()) {
            return new Response("Internal Server Error", 500);
        }

        return $event->getResponse();
    }

    /**
     * Displays a form to edit an existing document.
     *
     * @param Request $request The request.
     * @param int $id The document.
     * @return Response Returns the response.
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function editAction(Request $request, int $id): Response {

        $document = $this->findDocument($id, true);

        $type = $document->isDocument() ? "document" : "directory";

        $form = $this->createForm(DocumentFormType::class, $document);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(DocumentEvent::PRE_EDIT, $document);

            $document->setUpdatedAt(new DateTime());
            $this->getEntityManager()->flush();

            $this->dispatchDocumentEvent(DocumentEvent::POST_EDIT, $document);

            $this->notifySuccess($this->translate("DocumentController.editAction.success.$type"));

            [$route, $parameters] = $this->buildRedirectRoute($document);
            return $this->redirectToRoute($route, $parameters);
        }

        return $this->render("@WBWEDM/document/form.html.twig", [
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
    public function indexAction(Request $request): Response {

        $id = $request->attributes->get("id");

        $path  = ["name" => DocumentDataTablesProvider::DATATABLES_NAME];
        $query = null === $id ? [] : ["id" => $id];

        return $this->forward(DataTablesController::class . "::indexAction", $path, $query);
    }

    /**
     * Displays a form to move an existing document.
     *
     * @param Request $request The request.
     * @param int $id The document.
     * @return Response Returns the response.
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function moveAction(Request $request, int $id): Response {

        $document = $this->findDocument($id, true);

        $except = $document->isDirectory() ? $document : $document->getParent();
        $type   = $document->isDocument() ? "document" : "directory";

        /** @var DocumentRepository $repository */
        $repository = $this->getEntityManager()->getRepository(Document::class);

        $form = $this->createForm(MoveDocumentFormType::class, $document, [
            "entity.parent" => $repository->findAllDirectoriesExcept($except),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(DocumentEvent::PRE_MOVE, $document);

            $document->setUpdatedAt(new DateTime());
            $this->getEntityManager()->flush();

            $this->dispatchDocumentEvent(DocumentEvent::POST_MOVE, $document);

            $this->notifySuccess($this->translate("DocumentController.moveAction.success.{$type}"));

            [$route, $parameters] = $this->buildRedirectRoute($document);
            return $this->redirectToRoute($route, $parameters);
        }

        return $this->render("@WBWEDM/document/move.html.twig", [
            "form"     => $form->createView(),
            "document" => $document,
        ]);
    }

    /**
     * Creates a new document.
     *
     * @param Request $request The request.
     * @param int|null $id The parent.
     * @return Response Returns the response.
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function newAction(Request $request, int $id = null): Response {

        $parent = $this->findDocument($id, false);

        $document = new Document();
        $document->setCreatedAt(new DateTime());
        $document->setParent($parent);
        $document->setSize(0);
        $document->setType(DocumentInterface::TYPE_DIRECTORY);

        $form = $this->createForm(DocumentFormType::class, $document);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(DocumentEvent::PRE_NEW, $document);

            $em = $this->getEntityManager();
            $em->persist($document);
            $em->flush();

            $this->dispatchDocumentEvent(DocumentEvent::POST_NEW, $document);

            $this->notifySuccess($this->translate("DocumentController.newAction.success.directory"));

            [$route, $parameters] = $this->buildRedirectRoute($document);
            return $this->redirectToRoute($route, $parameters);
        }

        return $this->render("@WBWEDM/document/form.html.twig", [
            "form"     => $form->createView(),
            "document" => $document,
        ]);
    }

    /**
     * Uploads a document.
     *
     * @param Request $request The request.
     * @param int|null $id The parent.
     * @return Response Returns the response.
     * @throws Throwable Throws an exception if an error occurs.
     */
    public function uploadAction(Request $request, int $id = null): Response {

        $parent = $this->findDocument($id, false);

        $document = new Document();
        $document->setCreatedAt(new DateTime());
        $document->setParent($parent);
        $document->setSize(0);
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $form = $this->createForm(UploadDocumentFormType::class, $document);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(DocumentEvent::PRE_NEW, $document);

            $em = $this->getEntityManager();
            $em->persist($document);
            $em->flush();

            $this->dispatchDocumentEvent(DocumentEvent::POST_NEW, $document);

            $this->notifySuccess($this->translate("DocumentController.uploadAction.success.document"));

            [$route, $parameters] = $this->buildRedirectRoute($document);
            return $this->redirectToRoute($route, $parameters);
        }

        return $this->render("@WBWEDM/document/upload.html.twig", [
            "form"     => $form->createView(),
            "document" => $document,
        ]);
    }
}
