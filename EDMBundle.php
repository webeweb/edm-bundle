<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WBW\Bundle\EDMBundle\DependencyInjection\Compiler\EDMCompilerPass;

/**
 * EDM bundle.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle
 */
class EDMBundle extends Bundle {

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container) {
        $container->addCompilerPass(new EDMCompilerPass());
    }

}
