<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2018 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests;

use Symfony\Component\Finder\Finder;
use WBW\Bundle\CoreBundle\Tests\AbstractTestCase as TestCase;

/**
 * Abstract test case.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests
 * @abstract
 */
abstract class AbstractFrameworkTestCase extends TestCase {

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        $dir = getcwd();

        $finder = new Finder();
        $finder->sortByName()->files()->in($dir);

        foreach ($finder as $current) {
            if (1 === preg_match("/([0-9]{1,})\.download/", $current->getRealPath())) {
                unlink($current->getRealPath());
            }
        }
    }
}
