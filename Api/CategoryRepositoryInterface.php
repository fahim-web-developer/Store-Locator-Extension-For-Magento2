<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Api;

use \Acme\StoreLocator\Api\Data\CategoryInterface;

/**
 * Interface CategoryRepositoryInterface
 */
interface CategoryRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return \Acme\StoreLocator\Api\Data\CategoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id);

    /**
     * @param \Acme\StoreLocator\Api\Data\CategoryInterface $model
     *
     * @return \Acme\StoreLocator\Api\Data\CategoryInterface
     * @throws \Exception
     */
    public function save(CategoryInterface $model);

    /**
     * @param \Acme\StoreLocator\Api\Data\CategoryInterface $model
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(CategoryInterface $model);

    /**
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($id);
}
