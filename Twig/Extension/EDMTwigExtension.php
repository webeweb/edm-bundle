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
	 * @param TranslatorInterface $translator The translator service.
	 * @param StorageManager $storage The storage manager service.
	 */
	public function __construct(TranslatorInterface $translator, StorageManager $storage) {
		$this->storage		 = $storage;
		$this->translator	 = $translator;
	}

	/**
	 * Displays an EDM path.
	 *
	 * @param Document $document The document.
	 * @return string Returns the EDM path.
	 */
	public function edmPathFunction(Document $document = null) {
		return "/" . $this->storage->getVirtualPath($document);
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
			new Twig_SimpleFunction('edmPath', [$this, 'edmPathFunction']),
			new Twig_SimpleFunction('edmSize', [$this, 'edmSizeFunction']),
		];
	}

}
