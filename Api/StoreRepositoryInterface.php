<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Api;

use \Acme\StoreLocator\Api\Data\StoreInterface;

/**
 * Interface StoreRepositoryInterface
 */
interface StoreRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return \Acme\StoreLocator\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id);

    /**
     * @param \Acme\StoreLocator\Api\Data\StoreInterface $model
     *
     * @return \Acme\StoreLocator\Api\Data\StoreInterface
     * @throws \Exception
     */
    public function save(StoreInterface $model);

    /**
     * @param \Acme\StoreLocator\Api\Data\StoreInterface $model
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(StoreInterface $model);

    /**
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($id);
}
