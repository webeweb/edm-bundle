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
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Form\Type\Document\NewDocumentType;
use WBW\Bundle\EDMBundle\Form\Type\Document\UploadDocumentType;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Library\Core\Sort\Tree\Alphabetical\AlphabeticalTreeSort;

/**
 * Document controller.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 * @final
 */
final class DocumentController extends AbstractEDMController {

	/**
	 * Deletes a directory entity.
	 *
	 * @param Request $request The request.
	 * @param Document $document The document entity.
	 * @return Response Returns the response.
	 */
	public function deleteAction(Request $request, Document $document) {

		// Determines the type.
		if (true === $document->isDirectory()) {
			$type = "directory";
		} else {
			$type = "document";
		}

		try {

			// Preserve ID.
			$backup = clone $document;

			// Get the entities manager and delete the entity.
			$em = $this->getDoctrine()->getManager();
			$em->remove($document);
			$em->flush();

			// Delete the document.
			$this->get(StorageManager::SERVICE_NAME)->deleteDocument($backup);

			// Get the translation.
			$translation = $this->translate("DocumentController.deleteAction.success." . $type, [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);
		} catch (ForeignKeyConstraintViolationException $ex) {

			// Get the translation.
			$translation = $this->translate("DocumentController.deleteAction.danger." . $type, [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_DANGER, $translation);
		}

		// Return the response.
		return $this->redirectToRoute("edm_directory_index", [
				"id" => null === $document->getParent() ? null : $document->getParent()->getId(),
		]);
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
			$type = "directory";
		} else {
			$type = "document";
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

			// Get the translation.
			$translation = $this->translate("DocumentController.editAction.success." . $type, [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

			// Return the response.
			return $this->redirectToRoute("edm_directory_index", [
					"id" => null === $document->getParent() ? null : $document->getParent()->getId(),
			]);
		}

		// Return the response.
		return $this->render("@EDM/Document/new.html.twig", [
				"form"		 => $form->createView(),
				"document"	 => $document,
				"location"	 => $document
		]);
	}

	/**
	 * Lists all entities.
	 *
	 * @param Request $request The request.
	 * @param Document $parent The document entity.
	 * @return Response Returns the response.
	 */
	public function indexAction(Request $request, Document $parent = null) {

		// Get the entities manager.
		$em = $this->getDoctrine()->getManager();

		// Find the entities.
		$documents = $em->getRepository(Document::class)->findAllDirectoriesByParent($parent);

		// Check the documents.
		if (0 === count($documents)) {

			// Get the translation.
			$translation = $this->translate("DocumentController.indexAction.info", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_INFO, $translation);
		}

		// Return the response.
		return $this->render("@EDM/Document/index.html.twig", [
				"documents"	 => AlphabeticalTreeSort::sort(array_values($documents)),
				"parent"	 => $parent
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

			// Make the directory.
			$this->get(StorageManager::SERVICE_NAME)->saveDocument($directory);

			// Get the translation.
			$translation = $this->translate("DocumentController.newAction.success.directory", [], "EDMBundle");

			// Notity the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

			// Return the response.
			return $this->redirectToRoute("edm_directory_index", [
					"id" => null === $parent ? null : $parent->getId(),
			]);
		}

		// Return the response.
		return $this->render("@EDM/Document/new.html.twig", [
				"form"		 => $form->createView(),
				"document"	 => $directory,
				"location"	 => $parent,
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

			// Upload the document.
			$this->get(StorageManager::SERVICE_NAME)->saveDocument($document);

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
		return $this->render("@EDM/Document/upload.html.twig", [
				"form"		 => $form->createView(),
				"document"	 => $document,
		]);
	}

}
