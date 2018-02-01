<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Controller;

use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Event\DocumentEvent;
use WBW\Bundle\EDMBundle\Event\DocumentEvents;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentType;

/**
 * Dropzone controller.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 * @final
 */
final class DropzoneController extends AbstractEDMController {

    /**
     * Indexes an existing document entity.
     *
     * @param Request $request The request.
     * @param Document $directory The document entity.
     * @return Response Returns the response.
     */
    public function indexAction(Request $request, Document $directory = null) {

        // Get the entities manager.
        $em = $this->getDoctrine()->getManager();

        // Find the entities.
        $documents = $em->getRepository(Document::class)->findAllDocumentsByParent($directory);

        // Dispatch the event.
        if ($this->get("event_dispatcher")->hasListeners(DocumentEvents::DIRECTORY_OPEN) && null !== $directory) {
            $this->get("event_dispatcher")->dispatch(DocumentEvents::DIRECTORY_OPEN, new DocumentEvent(DocumentEvents::DIRECTORY_OPEN, $directory));
        }

        // Initialize the response.
        $response = new Response();
        $response->headers->set("Content-Type", "application/json");
        $response->headers->set("Content-Type", "text/json");

        // Send the headers.
        $response->sendHeaders();

        // Set the content.
        $response->setContent(json_encode($documents));

        // Return the response.
        return $response;
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
        $form = $this->createForm(UploadDocumentType::class, $document, [
            "csrf_protection" => false,
        ]);

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
            if ($this->get("event_dispatcher")->hasListeners(DocumentEvents::DOCUMENT_UPLOAD)) {
                $this->get("event_dispatcher")->dispatch(DocumentEvents::DOCUMENT_UPLOAD, new DocumentEvent(DocumentEvents::DOCUMENT_UPLOAD, $document));
            }
        }

        // Return the response.
        return $this->render("@EDM/Dropzone/upload.html.twig", [
                "form"              => $form->createView(),
                "document"          => $parent,
                "uploadMaxFilesize" => intval(ini_get('upload_max_filesize')),
        ]);
    }

}
