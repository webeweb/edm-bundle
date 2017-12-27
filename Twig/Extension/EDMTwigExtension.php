<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Twig\Extension;

use Symfony\Component\Translation\TranslatorInterface;
use Twig_Extension;
use Twig_SimpleFunction;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Library\Core\Utility\FileUtility;

/**
 * EDM Twig extension.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Twig\Extension
 * @final
 */
final class EDMTwigExtension extends Twig_Extension {

	/**
	 * Service name.
	 */
	const SERVICE_NAME = "webeweb.bundle.edmbundle.twig.extension.edm";

	/**
	 * Storage manager.
	 *
	 * @var StorageManager
	 */
	private $storage;

	/**
	 * Translator.
	 *
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * Constructor.
	 *
	 * @param StorageManager $manager The document manager service.
	 * @param TranslatorInterface $translator The translator service.
	 */
	public function __construct(StorageManager $manager, TranslatorInterface $translator) {
		$this->storage		 = $manager;
		$this->translator	 = $translator;
	}

	/**
	 * Displays an EDM relative path.
	 *
	 * @param Document $document The document.
	 * @return string Returns the EDM relative path.
	 */
	public function edmRelativePathFunction(Document $document = null) {
		if (is_null($document)) {
			return "/";
		}
		return "/" . $this->storage->getRelativePath($document);
	}

	/**
	 * Displays a size.
	 *
	 * @param Document $document The document.
	 * @return string Returns the size.
	 */
	public function edmSizeFunction(Document $document) {
		if (Document::TYPE_DIRECTORY === $document->getType()) {
			return implode(" ", [count($document->getChildrens()), $this->translator->trans("label.items", [], "EDMBundle")]);
		}
		return FileUtility::formatSize($document->getSize());
	}

	/**
	 * Get the Twig functions.
	 *
	 * @return array Returns the Twig functions.
	 */
	public function getFunctions() {
		return [
			new Twig_SimpleFunction('edmRelativePath', [$this, 'edmRelativePathFunction']),
			new Twig_SimpleFunction('edmSize', [$this, 'edmSizeFunction']),
		];
	}

}
