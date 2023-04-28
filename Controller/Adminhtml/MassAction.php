<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Controller\Adminhtml;

use \Magento\Backend\App\Action;
use \Magento\Ui\Component\MassAction\Filter;
use \Acme\StoreLocator\Model\ResourceModel\Store\CollectionFactory as StoreCollectionFactory;
use \Acme\StoreLocator\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use \Acme\StoreLocator\Api\StoreRepositoryInterface;
use \Acme\StoreLocator\Api\CategoryRepositoryInterface;

abstract class MassAction extends Action
{
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * @var \Acme\StoreLocator\Model\ResourceModel\Store\CollectionFactory
     */
    protected $storeCollectionFactory;

    /**
     * @var \Acme\StoreLocator\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \Acme\StoreLocator\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \Acme\StoreLocator\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param StoreCollectionFactory $storeCollectionFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param \Acme\StoreLocator\Api\StoreRepositoryInterface $storeRepository
     * @param \Acme\StoreLocator\Api\CategoryRepositoryInterface $categoryRepository
     * @internal param StoreCollectionFactory $collectionFactory
     * @internal param CategoryCollectionFactoryCategoryCollectionFactory $collectionFactory
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        StoreCollectionFactory $storeCollectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        StoreRepositoryInterface $storeRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->filter = $filter;
        $this->storeCollectionFactory = $storeCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeRepository = $storeRepository;
        $this->categoryRepository= $categoryRepository;
        parent::__construct($context);
    }
}
