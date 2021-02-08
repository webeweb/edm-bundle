edm-bundle
==========

[![Build Status](https://img.shields.io/github/workflow/status/webeweb/edm-bundle/build?style=flat-square)](https://github.com/webeweb/edm-bundle/actions)
[![Coverage Status](https://img.shields.io/coveralls/webeweb/edm-bundle/master.svg?style=flat-square)](https://coveralls.io/github/webeweb/edm-bundle?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/quality/g/webeweb/edm-bundle/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/webeweb/edm-bundle/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/webeweb/edm-bundle.svg?style=flat-square)](https://packagist.org/packages/webeweb/edm-bundle)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/webeweb/edm-bundle.svg?style=flat-square)](https://packagist.org/packages/webeweb/edm-bundle)
[![License](https://img.shields.io/packagist/l/webeweb/edm-bundle.svg?style=flat-square)](https://packagist.org/packages/webeweb/edm-bundle)
[![composer.lock](https://img.shields.io/badge/.lock-uncommited-important.svg?style=flat-square)](https://packagist.org/packages/webeweb/edm-bundle)

An Electronic Document Management for Symfony 2 and more.

> IMPORTANT NOTICE: This package is still under development. Any changes will be
> done without prior notice to consumers of this package. Of course this code
> will become stable at a certain point, but for now, use at your own risk.

Includes:

- [Dropzone 5.7.0](http://www.dropzonejs.com/)
- 460 SVG icons

If you like this package, pay me a beer (or a coffee)
[![paypal.me](https://img.shields.io/badge/paypal.me-webeweb-0070ba.svg?style=flat-square&logo=paypal)](https://www.paypal.me/webeweb)

## Compatibility

[![PHP](https://img.shields.io/packagist/php-v/webeweb/edm-bundle.svg?style=flat-square)](http://php.net)
[![Symfony](https://img.shields.io/badge/symfony-%5E3.4%7C%5E4.0-brightness.svg?style=flat-square)](https://symfony.com)

## Installation

Open a command console, enter your project directory and execute the following
command to download the latest stable version of this package:

```bash
$ composer require webeweb/edm-bundle
```

This command requires you to have Composer installed globally, as explained in
the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the
Composer documentation.

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
    public function registerBundles() {
        $bundles = [
            // ...
            new WBW\Bundle\BootstrapBundle\WBWBootstrapBundle(),
            new WBW\Bundle\CoreBundle\WBWCoreBundle(),
            new WBW\Bundle\EDMBundle\WBWEDMBundle(),
        ];

        // ...

        return $bundles;
    }
```

## Testing

To test the package, is better to clone this repository on your computer.
Open a command console and execute the following commands to download the latest
stable version of this package:

```bash
$ git clone https://github.com/webeweb/edm-bundle.git
$ cd edm-bundle
$ composer install
```

Once all required libraries are installed then do:

```bash
$ vendor/bin/phpunit
```

## License

`edm-bundle` is released under the MIT License. See the bundled [LICENSE](LICENSE)
file for details.

## Donate

If you like this work, please consider donating at
[![paypal.me](https://img.shields.io/badge/paypal.me-webeweb-0070ba.svg?style=flat-square&logo=paypal)](https://www.paypal.me/webeweb)
