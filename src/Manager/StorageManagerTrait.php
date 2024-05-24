<?php

/*
 * This file is part of the edm-bundle package.
 *
 * (c) 2019 WEBEWEB
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WBW\Bundle\EDMBundle\Manager;

/**
 * Storage manager trait.
 *
 * @author webeweb <https://github.com/webeweb>
 * @package WBW\Bundle\EDMBundle\Manager
 */
trait StorageManagerTrait {

    /**
     * Storage manager.
     *
     * @var StorageManager|null
     */
    private $storageManager;

    /**
     * Get the storage manager.
     *
     * @return StorageManager|null Returns the storage manager.
     */
    public function getStorageManager(): ?StorageManager {
        return $this->storageManager;
    }

    /**
     * Set the storage manager.
     *
     * @param StorageManager|null $storageManager The storage manager.
     * @return self Returns this instance.
     */
    public function setStorageManager(?StorageManager $storageManager): self {
        $this->storageManager = $storageManager;
        return $this;
    }
}
