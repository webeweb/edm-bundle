<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2021 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Command;

use WBW\Bundle\CoreBundle\Command\AbstractCommand as BaseCommand;
use WBW\Bundle\CoreBundle\Translation\TranslatorTrait;
use WBW\Bundle\EDMBundle\WBWEDMBundle;

/**
 * Abstract command.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Command
 */
class AbstractCommand extends BaseCommand {

    use TranslatorTrait {
        setTranslator as public;
    }

    /**
     * Translate.
     *
     * @param string $id The id.
     * @param array $parameters The parameters.
     * @param string|null $domain The domain.
     * @param string|null $locale The locale.
     * @return string Returns the translated id in case of success, id otherwise.
     */
    protected function translate(string $id, array $parameters = [], string $domain = null, string $locale = null): string {

        if (null === $this->getTranslator()) {
            return $id;
        }

        return $this->getTranslator()->trans($id, $parameters, WBWEDMBundle::getTranslationDomain(), "en");
    }
}
