<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Model;

use \Magento\Framework\Model\AbstractModel;
use \Acme\StoreLocator\Api\Data\StoreInterface;
use \Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\Model\Context;
use \Magento\Framework\Registry;
use \Magento\Directory\Model\CountryFactory;
use \Acme\StoreLocator\Api\CategoryRepositoryInterface;
use \Magento\Framework\Model\ResourceModel\AbstractResource;
use \Magento\Framework\Data\Collection\AbstractDb;
use \Acme\StoreLocator\Model\ResourceModel\Store as ResourceModel;

class Store extends AbstractModel implements StoreInterface, IdentityInterface
{
    const TYPE_DEALER = 1;
    const TYPE_SUBSIDIARY = 2;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const CACHE_TAG = 'acme_storelocator_store';

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    private $countryFactory;

    /**
     * @var \Acme\StoreLocator\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CountryFactory $countryFactory,
        CategoryRepositoryInterface $categoryRepository,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->countryFactory = $countryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->_cacheTag = 'acme_storelocator_store';
        $this->_eventPrefix = 'acme_storelocator_store';
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Prepare store's types.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ACTIVE => __('Enabled'), self::STATUS_INACTIVE => __('Disabled')];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry()
    {
        $country = $this->countryFactory->create()->load($this->getData(self::COUNTRY));
        return $country->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryName()
    {
        $category = $this->categoryRepository->get($this->getData('category_id'));
        return $category->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function setCountry($country)
    {
        $this->setData(self::COUNTRY, $country);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreationTime($creationTime)
    {
        $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdateTime($updateTime)
    {
        $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($isActive)
    {
        $this->setData(self::IS_ACTIVE, $isActive);
    }
}
