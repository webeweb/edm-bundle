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

use Symfony\Component\Routing\RouterInterface;
use Twig_Extension;

/**
 * EDM Twig extension.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\DependencyInjection
 * @final
 */
final class EDMTwigExtension extends Twig_Extension {

	/**
	 * Service name.
	 */
	const SERVICE_NAME = "webeweb.bundle.edmbundle.twig.extension.edm";

	/**
	 * Router
	 *
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * Constructor.
	 */
	public function __construct(RouterInterface $router) {
		$this->router = $router;
	}

}
