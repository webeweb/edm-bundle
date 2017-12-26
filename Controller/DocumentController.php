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
use WBW\Bundle\EDMBundle\Form\Type\DocumentType;

/**
 * Document controller.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 * @final
 */
final class DocumentController extends AbstractEDMController {

	/**
	 * Deletes a document entity.
	 *
	 * @param Request $request The request.
	 * @param Document $document The document entity.
	 * @return Response Returns the response.
	 */
	public function deleteAction(Request $request, Document $document) {

		// Get the entities manager and delete the entity.
		$em = $this->getDoctrine()->getManager();
		$em->remove($document);
		$em->flush();

		// Get the translation.
		$translation = $this->translate("DocumentController.deleteAction.success", [], "EDMBundle");

		// Notify the user.
		$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

		// Return the response.
		return $this->redirectToRoute("edm_directory_index", [
				"id" => is_null($document->getParent()) ? null : $document->getParent()->getId(),
		]);
	}

	/**
	 * Creates a new document entity.
	 *
	 * @param Request $request The request.
	 * @param Document $parent The directory entity.
	 * @return Response Returns the response.
	 */
	public function newAction(Request $request, Document $parent = null) {

		// Create the entity.
		$document = new Document();
		$document->setParent($parent);
		$document->setSize(0);
		$document->setType(Document::TYPE_DOCUMENT);

		// Create the form.
		$form = $this->createForm(DocumentType::class, $document);

		// Handle the request and check if the form is submitted and valid.
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			// Set the created at.
			$document->setCreatedAt(new DateTime());

			// Get the entities manager and insert the entity.
			$em = $this->getDoctrine()->getManager();
			$em->persist($document);
			$em->flush();

			// Make the directory.
			/* $this->get(DocumentManager::SERVICE_NAME)->makeDirectory($document); */

			// Get the translation.
			$translation = $this->translate("DocumentController.uploadAction.success", [], "EDMBundle");

			// Notity the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

			// Return the response.
			return $this->redirectToRoute("edm_directory_index", [
					"id" => is_null($parent) ? null : $parent->getId(),
			]);
		}

		// Return the response.
		return $this->render("@EDM/Document/form.html.twig", [
				"form"		 => $form->createView(),
				"document"	 => $document,
		]);
	}

}
