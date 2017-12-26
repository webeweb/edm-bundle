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

use Twig_Extension;
use Twig_SimpleFunction;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Manager\DocumentManager;

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
	 * Document manager.
	 *
	 * @var DocumentManager
	 */
	private $manager;

	/**
	 * Constructor.
	 *
	 * @param DocumentManager $manager The document manager.
	 */
	public function __construct(DocumentManager $manager) {
		$this->manager = $manager;
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
		return "/" . $this->manager->getRelativePath($document);
	}

	/**
	 * Get the Twig functions.
	 *
	 * @return array Returns the Twig functions.
	 */
	public function getFunctions() {
		return [
			new Twig_SimpleFunction('edmRelativePath', [$this, 'edmRelativePathFunction']),
		];
	}

}
