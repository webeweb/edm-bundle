<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use WBW\Bundle\CoreBundle\Tests\AbstractKernel;

/**
 * Test kernel.
 *
 * @author webeweb <https://github.com/webeweb/>
 */
class TestKernel extends AbstractKernel {

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): array {
        return [
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new WBW\Bundle\BootstrapBundle\WBWBootstrapBundle(),
            new WBW\Bundle\CoreBundle\WBWCoreBundle(),
            new WBW\Bundle\EDMBundle\WBWEDMBundle(),
            new WBW\Bundle\JQuery\DataTablesBundle\WBWJQueryDataTablesBundle(),
        ];
    }
}
