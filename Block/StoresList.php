<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Block;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\DataObject\IdentityInterface;
use \Acme\StoreLocator\Model\ResourceModel\Store\CollectionFactory as StoreCollectionFactory;
use \Acme\StoreLocator\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use \Magento\Framework\Json\Helper\Data as DataHelper;
use \Acme\StoreLocator\Helper\Config as ConfigHelper;
use \Acme\StoreLocator\Model\Category\Icon as CategoryIcon;
use \Acme\StoreLocator\Api\Data\StoreInterface;
use \Acme\StoreLocator\Model\ResourceModel\Store\Collection as StoreCollection;
use \Acme\StoreLocator\Model\Store;
use \Acme\StoreLocator\Model\Config\Source\GroupBy;

class StoresList extends Template implements IdentityInterface
{
    /**
     * @var \Acme\StoreLocator\Model\ResourceModel\Store\CollectionFactory
     */
    private $storeCollectionFactory;

    /**
     * @var \Acme\StoreLocator\Model\ResourceModel\Category\CollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    private $dataHelper;

    /**
     * @var \Acme\StoreLocator\Helper\Config
     */
    private $configHelper;

    /**
     * @var \Acme\StoreLocator\Model\Category\Icon
     */
    private $categoryIcon;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Acme\StoreLocator\Model\ResourceModel\Store\CollectionFactory $storeCollectionFactory
     * @param \Acme\StoreLocator\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Framework\Json\Helper\Data $dataHelper
     * @param ConfigHelper $configHelper
     * @param CategoryIcon $categoryIcon
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        StoreCollectionFactory $storeCollectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        DataHelper $dataHelper,
        ConfigHelper $configHelper,
        CategoryIcon $categoryIcon,
        array $data = []
    ) {
        $this->storeCollectionFactory = $storeCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->dataHelper = $dataHelper;
        $this->configHelper = $configHelper;
        $this->categoryIcon = $categoryIcon;
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();

        return parent::_prepareLayout();
    }

    private function _addBreadcrumbs()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );

            $breadcrumbsBlock->addCrumb(
                'storelocator',
                [
                    'label' => __('Store Locator'),
                ]
            );
        }
    }

    /**
     * @return string
     */
    public function getStoresJson()
    {
        if (!$this->hasData('stores_' . GroupBy::DONT_GROUP)) {
            $stores = [];

            $storesCollection = $this->storeCollectionFactory
                ->create();

            $storesCollection->addFilter('is_active', 1)
                ->addOrder(
                    StoreInterface::NAME,
                    StoreCollection::SORT_ORDER_DESC
                );

            if ($storesCollection->getSize() > 0) {
                /**
                 * @var \Acme\StoreLocator\Model\Store $store
                 */
                foreach ($storesCollection as $store) {
                    $elem = $store->getData();
                    $elem['country'] = $store->getCountry();
                    $elem['country_code'] = $store->getData('country');
                    $stores[] = $elem;
                }
            }

            $this->setData('stores_' . GroupBy::DONT_GROUP, base64_encode($this->dataHelper->jsonEncode($stores)));
        }

        return $this->getData('stores_' . GroupBy::DONT_GROUP);
    }

    /**
     * @return string
     */
    public function getStoresGroupedJson()
    {
        $groupBy = $this->getGroupStoresBy();

        if (!$this->hasData('stores_' . $groupBy)) {
            $stores = [];

            $storesCollection = $this->storeCollectionFactory
                ->create();

            $storesCollection->addFilter('is_active', 1)
                ->addFieldToSelect(['store_id', 'category_id', 'country']);

            if ($storesCollection->getSize() > 0) {
                switch ($groupBy) {
                    case GroupBy::COUNTRY:
                        $stores = $this->groupStoresByCountry($storesCollection);
                        break;
                    case GroupBy::CATEGORY:
                        $stores = $this->groupStoresByCategory($storesCollection);
                        break;
                    case GroupBy::COUNTRY_CATEGORY:
                        $stores = $this->groupStoresByCountryCategory($storesCollection);
                        break;
                    case GroupBy::CATEGORY_COUNTRY:
                        $stores = $this->groupStoresByCategoryCountry($storesCollection);
                        break;
                    default:
                        $stores = false;
                        break;
                }
            }

            $this->setData('stores_' . $groupBy, base64_encode($this->dataHelper->jsonEncode($stores)));
        }

        return $this->getData('stores_' . $groupBy);
    }

    /**
     * @return mixed
     */
    public function getCategoriesJson()
    {
        if (!$this->hasData('categories')) {
            $categories = [];
            $collection = $this->categoryCollectionFactory->create();
            $collection->addFilter('is_active', 1);
            $collection->addFieldToSelect(['category_id', 'name', 'icon']);

            if (!empty($collection)) {
                foreach ($collection as $element) {
                    $categories[$element->getId()]['name'] = $element->getName();
                    $categories[$element->getId()]['icon'] = '';
                    if ($element->getIcon()) {
                        $icon = $this->categoryIcon->getBaseUrl().$element->getIcon();
                        $categories[$element->getId()]['icon'] = $icon;
                    }
                }
            }
            $this->setData('categories', base64_encode($this->dataHelper->jsonEncode($categories)));
        }
        return $this->getData('categories');
    }

    /**
     * @return string|null
     */
    public function getGoogleApiKey()
    {
        return $this->configHelper->getGoogleApiKeyFrontend();
    }

    /**
     * @return int|null
     */
    public function getGroupStoresBy()
    {
        return $this->configHelper->getGroupStoresBy();
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return [Store::CACHE_TAG . '_' . 'list'];
    }

    /**
     * @param $collection
     * @return array
     */
    private function groupStoresByCountry($collection)
    {
        $stores = [];

        /**
         * @var \Acme\StoreLocator\Model\Store $store
         */
        foreach ($collection as $store) {
            $elemId = $store->getId();
            $stores[$store->getData('country')]['stores'][] = (int)$elemId;
            if (!array_key_exists('count', $stores[$store->getData('country')])) {
                $stores[$store->getData('country')]['name'] = $store->getCountry();
                $stores[$store->getData('country')]['count'] = 0;
                $stores[$store->getData('country')]['count_all'] = 0;
            }
            $stores[$store->getData('country')]['count']++;
            $stores[$store->getData('country')]['count_all']++;
        }

        return $stores;
    }

    /**
     * @param $collection
     * @return array
     */
    private function groupStoresByCategory($collection)
    {
        $stores = [];

        /**
         * @var \Acme\StoreLocator\Model\Store $store
         */
        foreach ($collection as $store) {
            $elemId = $store->getId();
            $stores[$store->getData('category_id')]['stores'][] = (int)$elemId;
            if (!array_key_exists('count', $stores[$store->getData('category_id')])) {
                $stores[$store->getData('category_id')]['name'] = $store->getCategoryName();
                $stores[$store->getData('category_id')]['count'] = 0;
                $stores[$store->getData('category_id')]['count_all'] = 0;
            }
            $stores[$store->getData('category_id')]['count']++;
            $stores[$store->getData('category_id')]['count_all']++;
        }

        return $stores;
    }

    /**
     * @param $collection
     * @return array
     */
    private function groupStoresByCountryCategory($collection)
    {
        $stores = [];

        /**
         * @var \Acme\StoreLocator\Model\Store $store
         */
        foreach ($collection as $store) {
            $elemId = $store->getId();
            $country = $store->getData('country');
            $categoryId = $store->getData('category_id');
            $stores[$country]['elements'][$categoryId]['stores'][] = (int)$elemId;
            if (!array_key_exists('count', $stores[$country])) {
                $stores[$country]['name'] = $store->getCountry();
                $stores[$country]['count'] = 0;
                $stores[$country]['count_all'] = 0;
            }
            if (!array_key_exists('count', $stores[$country]['elements'][$categoryId])) {
                $stores[$country]['elements'][$categoryId]['name'] = $store->getCategoryName();
                $stores[$country]['elements'][$categoryId]['count'] = 0;
                $stores[$country]['elements'][$categoryId]['count_all'] = 0;
            }
            $stores[$country]['count']++;
            $stores[$country]['count_all']++;
            $stores[$country]['elements'][$categoryId]['count']++;
            $stores[$country]['elements'][$categoryId]['count_all']++;
        }

        return $stores;
    }

    /**
     * @param $collection
     * @return array
     */
    private function groupStoresByCategoryCountry($collection)
    {
        $stores = [];

        /**
         * @var \Acme\StoreLocator\Model\Store $store
         */
        foreach ($collection as $store) {
            $elemId = $store->getId();
            $country = $store->getData('country');
            $categoryId = $store->getData('category_id');
            $stores[$categoryId]['elements'][$country]['stores'][] = (int)$elemId;
            if (!array_key_exists('count', $stores[$categoryId])) {
                $stores[$categoryId]['name'] = $store->getCategoryName();
                $stores[$categoryId]['count'] = 0;
                $stores[$categoryId]['count_all'] = 0;
            }
            if (!array_key_exists('count', $stores[$categoryId]['elements'][$country])) {
                $stores[$categoryId]['elements'][$country]['name'] = $store->getCountry();
                $stores[$categoryId]['elements'][$country]['count'] = 0;
                $stores[$categoryId]['elements'][$country]['count_all'] = 0;
            }
            $stores[$categoryId]['count']++;
            $stores[$categoryId]['count_all']++;
            $stores[$categoryId]['elements'][$country]['count']++;
            $stores[$categoryId]['elements'][$country]['count_all']++;
        }

        return $stores;
    }
}
