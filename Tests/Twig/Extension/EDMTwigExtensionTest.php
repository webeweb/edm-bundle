<?php

/**
 * This file is part of the edm-bundle package.
 *
 * (c) 2017 NdC/WBW
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Tests\Twig\Extension;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Node;
use Twig_SimpleFunction;
use WBW\Bundle\EDMBundle\Entity\Document;
use WBW\Bundle\EDMBundle\Manager\StorageManager;
use WBW\Bundle\EDMBundle\Twig\Extension\EDMTwigExtension;

/**
 * EDM Twig extension test.
 *
 * @author NdC/WBW <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Tests\Twig\Extension
 * @final
 */
final class EDMTwigExtensionTest extends PHPUnit_Framework_TestCase {

	/**
	 * Translator.
	 *
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp() {

		$translate = function ($id, array $parameters = [], $domain = null, $locale = null) {
			return $id;
		};

		$this->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
		$this->translator->expects($this->any())->method("trans")->willReturnCallback($translate);
	}

	/**
	 * Tests the getFunctions() method.
	 *
	 * @return void
	 */
	public function testGetFunctions() {

		$obj = new EDMTwigExtension($this->translator, new StorageManager(getcwd()));

		$res = $obj->getFunctions();

		$this->assertCount(2, $res);
		$this->assertInstanceOf(Twig_SimpleFunction::class, $res[0]);
		$this->assertEquals("edmPath", $res[0]->getName());
		$this->assertEquals([$obj, "edmPathFunction"], $res[0]->getCallable());
		$this->assertEquals([], $res[0]->getSafe(new Twig_Node()));

		$this->assertInstanceOf(Twig_SimpleFunction::class, $res[1]);
		$this->assertEquals("edmSize", $res[1]->getName());
		$this->assertEquals([$obj, "edmSizeFunction"], $res[1]->getCallable());
		$this->assertEquals([], $res[1]->getSafe(new Twig_Node()));
	}

	/**
	 * Tests the edmPathFunction() method.
	 *
	 * @return void
	 * @depends testGetFunctions
	 */
	public function testEdmPathFunction() {

		$obj = new EDMTwigExtension($this->translator, new StorageManager(getcwd()));

		$this->assertEquals("/", $obj->edmPathFunction(null));
		$this->assertEquals("/phpunit", $obj->edmPathFunction((new Document())->setType(Document::TYPE_DIRECTORY)->setName("phpunit")));
	}

	/**
	 * Tests the edmSizeFunction() method.
	 *
	 * @return void
	 * @depends testGetFunctions
	 */
	public function testEdmSizeFunction() {

		$obj = new EDMTwigExtension($this->translator, new StorageManager(getcwd()));

		$this->assertEquals("0 label.items", $obj->edmSizeFunction((new Document())->setType(Document::TYPE_DIRECTORY)));
		$this->assertEquals("1.00 KB", $obj->edmSizeFunction((new Document())->setType(Document::TYPE_DOCUMENT)->setSize(1000)));
	}

}
