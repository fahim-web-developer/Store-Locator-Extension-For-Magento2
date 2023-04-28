<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Controller\Adminhtml;

use \Magento\Backend\App\Action;
use \Magento\Framework\View\Result\PageFactory;
use \Acme\StoreLocator\Api\StoreRepositoryInterface;
use \Acme\StoreLocator\Api\Data\StoreInterfaceFactory;
use \Acme\StoreLocator\Helper\Config as ConfigHelper;

abstract class Stores extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Acme\StoreLocator\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \Acme\StoreLocator\Api\Data\StoreInterfaceFactory
     */
    protected $storeFactory;

    /**
     * @var \Acme\StoreLocator\Helper\Config
     */
    private $configHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Acme\StoreLocator\Api\StoreRepositoryInterface $storeRepository
     * @param StoreInterfaceFactory $storeFactory
     * @param \Acme\StoreLocator\Helper\Config $configHelper
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        StoreRepositoryInterface $storeRepository,
        StoreInterfaceFactory $storeFactory,
        ConfigHelper $configHelper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->storeRepository = $storeRepository;
        $this->storeFactory = $storeFactory;
        $this->configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Init layout, menu and breadcrumb
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Acme_StoreLocator::stores_list');
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Acme_StoreLocator::stores');
    }

    /**
     * @return $this|bool
     */
    protected function checkGoogleApiKey()
    {
        if ($this->configHelper->getGoogleApiKeyBackend() === null) {
            $this->messageManager->addErrorMessage(__(
                'Google Api Key not set! Check settings in Stores -> Configuration -> Acme -> Store Locator'
            ));
            /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/index');
        }
        return false;
    }
}
