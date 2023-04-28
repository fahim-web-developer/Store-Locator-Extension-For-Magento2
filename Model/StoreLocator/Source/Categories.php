<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Model\StoreLocator\Source;

use \Magento\Framework\Data\OptionSourceInterface;
use \Acme\StoreLocator\Model\ResourceModel\Category\CollectionFactory;
use \Acme\StoreLocator\Model\Category;

class Categories implements OptionSourceInterface
{
    /**
     * @var \Acme\StoreLocator\Model\Store
     */
    private $categoriesCollectionFactory;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->categoriesCollectionFactory = $collectionFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $collection = $this->categoriesCollectionFactory->create();
        $collection->addFieldToFilter('is_active', Category::STATUS_ACTIVE);
        $collection->addFieldToSelect(['category_id', 'name']);
        $collection->load();

        foreach ($collection as $category) {
            $options[] = ['label' => $category->getName(), 'value' => $category->getId()];
        }
        return $options;
    }
}
