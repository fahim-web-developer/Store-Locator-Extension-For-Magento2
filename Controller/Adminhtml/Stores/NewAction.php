<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Controller\Adminhtml\Stores;

use \Acme\StoreLocator\Controller\Adminhtml\Stores;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use \Acme\StoreLocator\Api\StoreRepositoryInterface;
use \Acme\StoreLocator\Helper\Config as ConfigHelper;
use \Magento\Backend\Model\View\Result\ForwardFactory;
use \Acme\StoreLocator\Api\Data\StoreInterfaceFactory;

class NewAction extends Stores
{
    /**
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    protected $resultForwardFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Acme\StoreLocator\Api\StoreRepositoryInterface $storeRepository
     * @param StoreInterfaceFactory $storeFactory
     * @param \Acme\StoreLocator\Helper\Config $configHelper
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        StoreRepositoryInterface $storeRepository,
        StoreInterfaceFactory $storeFactory,
        ConfigHelper $configHelper,
        ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context, $resultPageFactory, $storeRepository, $storeFactory, $configHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($error = $this->checkGoogleApiKey()) {
            return $error;
        }
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
