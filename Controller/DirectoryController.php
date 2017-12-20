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
use WBW\Bundle\EDMBundle\Form\DirectoryType;
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

			// Make the directory.
			if ($this->get(DocumentManager::SERVICE_NAME)->removeDirectory($directory) === true) {

				// Get the entities manager and delete the entity.
				$em = $this->getDoctrine()->getManager();
				$em->remove($directory);
				$em->flush();

				// Get the translation.
				$translation = $this->translate("DirectoryController.deleteAction.success", [], "EDMBundle");

				// Notify the user.
				$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);
			}
		} catch (ContextErrorException $ex) {

			// Get the translation.
			$translation = $this->translate("DirectoryController.deleteAction.danger", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_DANGER, $translation);
		} catch (ForeignKeyConstraintViolationException $ex) {

			// Get the translation.
			$translation = $this->translate("DirectoryController.deleteAction.warning", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_WARNING, $translation);
		}

		// Return the response.
		return $this->redirectToRoute("edm_directory_index");
	}

	/**
	 * Displays a form to edit an existing directory entity.
	 *
	 * @param Request $request The request.
	 * @param Document $directory The directory entity.
	 * @return Response Returns the response.
	 */
	public function editAction(Request $request, Document $directory) {

		// Get the entities manager.
		$em = $this->getDoctrine()->getManager();

		// Find all directory entities.
		$directories = $em->getRepository(Document::class)->findAllDirectories();

		// Create the form.
		$form = $this->createForm(DirectoryType::class, $directory, [
			"entity.parent" => $directories,
		]);

		// Backup the directory..
		$directory->backup();

		// Handle the request and check if the form is submitted and valid.
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			// Rename the directory.
			if ($this->get(DocumentManager::SERVICE_NAME)->renameDirectory($directory) === true) {

				// Get the entities manager and update the entity.
				$this->getDoctrine()->getManager()->flush();

				// Get the translation.
				$translation = $this->translate("CategorieController.editAction.success", [], "EDMBundle");

				// Notify the user.
				$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

				// Return the response.
				return $this->redirectToRoute("edm_directory_index");
			}

			// Get the translation.
			$translation = $this->translate("CategorieController.editAction.success", [], "EDMBundle");

			// Notify the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);
		}

		// Return the response.
		return $this->render("@EDM/Directory/form.html.twig", [
				"form"		 => $form->createView(),
				"directory"	 => $directory,
		]);
	}

	/**
	 * Lists all catégorie entities.
	 *
	 * @return Response Returns the response.
	 */
	public function indexAction() {

		// Get the entities manager.
		$em = $this->getDoctrine()->getManager();

		// Find the entities.
		$directories = $em->getRepository(Document::class)->findAllDirectories();

		// Return the response.
		return $this->render("@EDM/Directory/index.html.twig", [
				"directories" => AlphabeticalTreeSort::sort(array_values($directories)),
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
		if (!is_null($parent)) {
			$directory->setParent($parent);
		}
		$directory->setSize(0);
		$directory->setType(Document::TYPE_DIRECTORY);

		// Get the entities manager.
		$em = $this->getDoctrine()->getManager();

		// Find all directory entities.
		$directories = $em->getRepository(Document::class)->findAllDirectories();

		// Create the form.
		$form = $this->createForm(DirectoryType::class, $directory, [
			"entity.parent" => AlphabeticalTreeSort::sort(array_values($directories)),
		]);

		// Handle the request and check if the form is submitted and valid.
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			// Make the directory.
			if ($this->get(DocumentManager::SERVICE_NAME)->makeDirectory($directory) === true) {

				// Set the created at.
				$directory->setCreatedAt(new DateTime());

				// Get the entities manager and insert the entity.
				$em = $this->getDoctrine()->getManager();
				$em->persist($directory);
				$em->flush();

				// Get the translation.
				$translation = $this->translate("DirectoryController.newAction.success", [], "EDMBundle");

				// Notity the user.
				$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);

				// Return the response.
				return $this->redirectToRoute("edm_directory_index");
			}

			// Get the translation.
			$translation = $this->translate("DirectoryController.newAction.warning", [], "EDMBundle");

			// Notity the user.
			$this->notify($request, self::NOTIFICATION_SUCCESS, $translation);
		}

		// Return the response.
		return $this->render("@EDM/Directory/form.html.twig", [
				"form"		 => $form->createView(),
				"directory"	 => $directory,
		]);
	}

}
