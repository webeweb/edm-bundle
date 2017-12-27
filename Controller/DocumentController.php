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
use WBW\Bundle\EDMBundle\Form\Type\DocumentEditType;
use WBW\Bundle\EDMBundle\Form\Type\DocumentMoveType;
use WBW\Bundle\EDMBundle\Manager\StorageManager;

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

		// Remove the document.
		$this->get(StorageManager::SERVICE_NAME)->removeDocument($document);

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
	 * Displays a form to move an existing directory entity.
	 *
	 * @param Request $request The request.
	 * @param Document $document The directory entity.
	 * @return Response Returns the response.
	 */
	public function moveAction(Request $request, Document $document) {

		// Create the form.
		$form = $this->createForm(DocumentMoveType::class, $document, [
			"entity.parent" => $this->getDoctrine()->getManager()->getRepository(Document::class)->findAllDirectory($document->getParent()),
		]);

		// Handle the request and check if the form is submitted and valid.
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			// Rename the document.
			$this->get(StorageManager::SERVICE_NAME)->renameDirectory($document);

			// Set the updated at.
			$document->setUpdatedAt(new DateTime());

			// Get the entities manager and update the entity.
			$this->getDoctrine()->getManager()->flush();

			// Get the translation.
			$translation = $this->translate("DocumentController.moveAction.success", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

			// Return the response.
			return $this->redirectToRoute("edm_directory_index", [
					"id" => null === $document->getParent() ? null : $document->getParent()->getId(),
			]);
		}

		// Return the response.
		return $this->render("@EDM/Document/move.html.twig", [
				"form"		 => $form->createView(),
				"document"	 => $document,
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
		$form = $this->createForm(DocumentEditType::class, $document);

		// Handle the request and check if the form is submitted and valid.
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			// Upload the document.
			$this->get(StorageManager::SERVICE_NAME)->uploadDocument($document);

			// Set the created at.
			$document->setCreatedAt(new DateTime());

			// Get the entities manager and insert the entity.
			$em = $this->getDoctrine()->getManager();
			$em->persist($document);
			$em->flush();

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
