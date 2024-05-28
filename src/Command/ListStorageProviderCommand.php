<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2021 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace WBW\Bundle\EDMBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WBW\Bundle\CommonBundle\Command\AbstractProviderListCommand;
use WBW\Bundle\EDMBundle\Manager\StorageManagerTrait;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;
use WBW\Bundle\EDMBundle\WBWEDMBundle;

/**
 * List storage provider command.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Command
 */
class ListStorageProviderCommand extends AbstractProviderListCommand {

    use StorageManagerTrait {
        setStorageManager as public;
    }

    /**
     * Command name.
     *
     * @var string
     */
    public const COMMAND_NAME = "wbw:edm:provider:list";

    /**
     * Service name.
     *
     * @var string
     */
    public const SERVICE_NAME = "wbw.edm.command.list_provider";

    /**
     * {@inheritDoc}
     */
    protected function configure(): void {
        $this
            ->setDescription("List the storage providers")
            ->setHelp(static::formatHelp("list the storage providers"))
            ->setName(self::COMMAND_NAME);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {

        $io = $this->newStyle($input, $output);
        $this->displayTitle($io, $this->getDescription());

        $rows = [];

        /** @var StorageProviderInterface $current */
        foreach ($this->getStorageManager()->getProviders() as $current) {
            $rows[] = $this->renderRow($current);
        }

        $this->sortRows($rows);

        $io->table($this->getHeaders(), $rows);
        $this->displayFooter($io, count($rows), "command.footer.provider_list.success", "command.footer.provider_list.warning", WBWEDMBundle::getTranslationDomain(), "en");

        return 0;
    }

    /**
     * Get the headers.
     *
     * @return string[] Returns the headers.
     */
    protected function getHeaders(): array {

        return [
            $this->translate("command.header.class", [], WBWEDMBundle::getTranslationDomain(), "en"),
        ];
    }

    /**
     * Render a row.
     *
     * @param StorageProviderInterface $provider The provider.
     * @return string[] Returns the rendered row.
     */
    protected function renderRow(StorageProviderInterface $provider): array {

        return [
            get_class($provider),
        ];
    }
}
