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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WBW\Bundle\EDMBundle\Manager\StorageManagerTrait;
use WBW\Bundle\EDMBundle\Provider\StorageProviderInterface;

/**
 * List storage provider command.
 *
 * @author webeweb <https://github.com/webeweb/>
 * @package WBW\Bundle\EDMBundle\Command
 */
class ListStorageProviderCommand extends AbstractCommand {

    use StorageManagerTrait {
        setStorageManager as public;
    }

    /**
     * Command help.
     *
     * @var string
     */
    const COMMAND_HELP = <<< EOT
The <info>%command.name%</info> command list the storage providers.

    <info>php %command.full_name%</info>


EOT;

    /**
     * Command name.
     *
     * @var string
     */
    const COMMAND_NAME = "wbw:edm:list-provider";

    /**
     * Service name.
     *
     * @var string
     */
    const SERVICE_NAME = "wbw.edm.command.list_provider";

    /**
     * {@inheritDoc}
     */
    protected function configure() {
        $this
            ->setDescription("List the storage providers")
            ->setHelp(self::COMMAND_HELP)
            ->setName(self::COMMAND_NAME);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        $io = $this->newStyle($input, $output);
        $this->displayTitle($io, $this->getDescription());

        $rows = [];

        foreach ($this->getStorageManager()->getProviders() as $current) {
            $rows[] = $this->renderRow($current);
        }

        $this->sortRows($rows);

        $io->table($this->getHeaders(), $rows);

        return 0;
    }

    /**
     * Get the headers.
     *
     * @return string[] Returns the headers.
     */
    protected function getHeaders(): array {
        return [
            $this->translate("label.class"),
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

    /**
     * Sort the rows.
     *
     * @param array $rows The rows.
     * @return void
     */
    protected function sortRows(array &$rows): void {
        usort($rows, function(array $a, array $b) {
            return strcmp($a[0], $b[0]);
        });
    }

}
