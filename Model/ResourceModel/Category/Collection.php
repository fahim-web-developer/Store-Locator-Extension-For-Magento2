<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Model\ResourceModel\Category;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use \Acme\StoreLocator\Model\Category as Model;
use \Acme\StoreLocator\Model\ResourceModel\Category as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'category_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
