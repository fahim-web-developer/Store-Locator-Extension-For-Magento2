<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Controller\Index;

class Index extends \Acme\StoreLocator\Controller\Index
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Store Locator'));

        return $resultPage;
    }
}
