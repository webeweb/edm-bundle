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
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\DataTables\Provider\DocumentDataTablesProvider;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentFormType;
use WBW\Bundle\EDMBundle\Model\DocumentInterface;
use WBW\Bundle\EDMBundle\Repository\DocumentRepository;
use WBW\Bundle\JQuery\DataTablesBundle\Controller\DataTablesController;

/**
 * Dropzone controller.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Controller
 */
class DropzoneController extends AbstractController {

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "wbw.edm.controller.dropzone";

    /**
     * Index a directory.
     *
     * @param Document|null $directory The directory.
     * @return Response Returns the response.
     */
    public function indexAction(Document $directory = null): Response {

        /** @var DocumentRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Document::class);

        $entities = $repository->findAllDocumentsByParent($directory);

        return new JsonResponse($entities);
    }

    /**
     * Serialize an existing document.
     *
     * @param Document $document The document.
     * @return Response Returns the response.
     */
    public function serializeAction(Document $document): Response {
        return $this->forward(DataTablesController::class . "::serializeAction", [
            "name" => DocumentDataTablesProvider::DATATABLES_NAME,
            "id"   => $document->getId(),
        ]);
    }

    /**
     * Upload a document.
     *
     * @param Request $request The request.
     * @param Document|null $parent The parent.
     * @return Response Returns the response.
     * @throws Exception Throws an exception if an error occurs.
     */
    public function uploadAction(Request $request, Document $parent = null): Response {

        $document = new Document();
        $document->setCreatedAt(new DateTime());
        $document->setParent($parent);
        $document->setSize(0);
        $document->setType(DocumentInterface::TYPE_DOCUMENT);

        $form = $this->createForm(UploadDocumentFormType::class, $document, [
            "csrf_protection" => false,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->dispatchDocumentEvent(DocumentEvent::PRE_NEW, $document);

            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            $this->dispatchDocumentEvent(DocumentEvent::POST_NEW, $document);

            return new JsonResponse($this->prepareActionResponse(200, "DropzoneController.uploadAction.success"));
        }

        return $this->render("@WBWEDM/Dropzone/upload.html.twig", [
            "form"              => $form->createView(),
            "document"          => $parent,
            "uploadMaxFilesize" => intval(ini_get("upload_max_filesize")),
        ]);
    }
}
