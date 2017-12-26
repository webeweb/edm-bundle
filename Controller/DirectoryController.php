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
use WBW\Bundle\EDMBundle\Form\Type\DirectoryType;
use WBW\Bundle\EDMBundle\Manager\DocumentManager;
use WBW\Library\Core\Sort\Tree\Alphabetical\AlphabeticalTreeSort;

/**
 * Directory controller.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Controller
 * @final
 */
final class DirectoryController extends AbstractEDMController {

	/**
	 * Deletes a directory entity.
	 *
	 * @param Request $request The request.
	 * @param Document $directory The directory entity.
	 * @return Response Returns the response.
	 */
	public function deleteAction(Request $request, Document $directory) {

		try {

			// Get the entities manager and delete the entity.
			$em = $this->getDoctrine()->getManager();
			$em->remove($directory);
			$em->flush();

			// Remove the directory.
			$this->get(DocumentManager::SERVICE_NAME)->removeDirectory($directory);

			// Get the translation.
			$translation = $this->translate("DirectoryController.deleteAction.success", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);
		} catch (ForeignKeyConstraintViolationException $ex) {

			// Get the translation.
			$translation = $this->translate("DirectoryController.deleteAction.warning", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_WARNING, $translation);
		}

		// Return the response.
		return $this->redirectToRoute("edm_directory_index", [
				"id" => is_null($directory->getParent()) ? null : $directory->getParent()->getId(),
		]);
	}

	/**
	 * Displays a form to edit an existing directory entity.
	 *
	 * @param Request $request The request.
	 * @param Document $directory The directory entity.
	 * @return Response Returns the response.
	 */
	public function editAction(Request $request, Document $directory) {

		// Create the form.
		$form = $this->createForm(DirectoryType::class, $directory);

		// Backup the directory..
		$directory->backup();

		// Handle the request and check if the form is submitted and valid.
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			// Set the updated at.
			$directory->setUpdatedAt(new DateTime());

			// Get the entities manager and update the entity.
			$this->getDoctrine()->getManager()->flush();

			// Rename the directory.
			$this->get(DocumentManager::SERVICE_NAME)->renameDirectory($directory);

			// Get the translation.
			$translation = $this->translate("DirectoryController.editAction.success", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

			// Return the response.
			return $this->redirectToRoute("edm_directory_index", [
					"id" => is_null($directory->getParent()) ? null : $directory->getParent()->getId(),
			]);
		}

		// Return the response.
		return $this->render("@EDM/Directory/form.html.twig", [
				"form"		 => $form->createView(),
				"directory"	 => $directory,
		]);
	}

	/**
	 * Indexes a directory entity.
	 *
	 * @param Request $request The request.
	 * @param Document $parent The parent entity.
	 * @return Response Returns the response.
	 */
	public function indexAction(Request $request, Document $parent = null) {

		// Get the entities manager.
		$em = $this->getDoctrine()->getManager();

		// Find the documents.
		$documents = $em->getRepository(Document::class)->findByParent($parent);

		// Check the documents.
		if (count($documents) === 0) {

			// Get the translation.
			$translation = $this->translate("DirectoryController.indexAction.info", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_INFO, $translation);
		}

		// Return the response.
		return $this->render("@EDM/Directory/index.html.twig", [
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
		$form = $this->createForm(DirectoryType::class, $directory);

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
			$this->get(DocumentManager::SERVICE_NAME)->makeDirectory($directory);

			// Get the translation.
			$translation = $this->translate("DirectoryController.newAction.success", [], "EDMBundle");

			// Notity the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

			// Return the response.
			return $this->redirectToRoute("edm_directory_index", [
					"id" => is_null($parent) ? null : $parent->getId(),
			]);
		}

		// Return the response.
		return $this->render("@EDM/Directory/form.html.twig", [
				"form"		 => $form->createView(),
				"directory"	 => $directory,
		]);
	}

}
