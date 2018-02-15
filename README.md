edm-bundle
==========

[![Build Status](https://travis-ci.org/webeweb/edm-bundle.svg?branch=master)](https://travis-ci.org/webeweb/edm-bundle) [![Coverage Status](https://coveralls.io/repos/github/webeweb/edm-bundle/badge.svg?branch=master)](https://coveralls.io/github/webeweb/edm-bundle?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webeweb/edm-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/webeweb/edm-bundle/?branch=master) [![Latest Stable Version](https://poser.pugx.org/webeweb/edm-bundle/v/stable)](https://packagist.org/packages/webeweb/edm-bundle) [![Latest Unstable Version](https://poser.pugx.org/webeweb/edm-bundle/v/unstable)](https://packagist.org/packages/webeweb/edm-bundle) [![License](https://poser.pugx.org/webeweb/edm-bundle/license)](https://packagist.org/packages/webeweb/edm-bundle) [![composer.lock](https://poser.pugx.org/webeweb/edm-bundle/composerlock)](https://packagist.org/packages/webeweb/edm-bundle) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/8af834f5-a5d7-47b7-b24a-b42c42e1489e/mini.png)](https://insight.sensiolabs.com/projects/8af834f5-a5d7-47b7-b24a-b42c42e1489e)

An Electronic Document Management for Symfony2.

> IMPORTANT NOTICE: This package is still under development. Any changes will be
> done without prior notice to consumers of this package. Of course this code
> will become stable at a certain point, but for now, use at your own risk.

---

## Compatibility

[![PHP](https://img.shields.io/badge/PHP-%5E5.6%7C%5E7.0-blue.svg)](http://php.net) [![HHVM](https://img.shields.io/badge/HHVM-ready-orange.svg)](https://hhvm.com/) [![Symfony](https://img.shields.io/badge/Symfony-%5E2.8%7C%5E3.0-brightgreen.svg)](https://symfony.com)

---

## Installation

Open a command console, enter your project directory and execute the following
command to download the latest stable version of this package:

```bash
$ composer require webeweb/edm-bundle "~1.0@dev"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the
Composer documentation.

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
	public function registerBundles() {
		$bundles = [
			// ...
			new WBW\Bundle\BootstrapBundle\BootstrapBundle(),
			new WBW\Bundle\EDMBundle\EDMBundle(),
		];

		// ...

		return $bundles;
    }
```

---

## Testing

To test the package, is better to clone this repository on your computer.
Open a command console and execute the following commands to download the latest
stable version of this package:

```bash
$ mkdir edm-bundle
$ cd edm-bundle
$ git clone git@github.com:webeweb/edm-bundle.git .
$ composer install
```

Once all required libraries are installed then do:

```bash
$ vendor/bin/phpunit
```

---

## Provides

- [Dropzone 5.3.0](http://http://www.dropzonejs.com/)

---

## License

edm-bundle is released under the LGPL License. See the bundled
[LICENSE](LICENSE) file for details.
