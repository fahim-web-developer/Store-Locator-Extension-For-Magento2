<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Controller\Adminhtml\Stores;

use \Acme\StoreLocator\Controller\Adminhtml\Stores;

class Index extends Stores
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()->prepend(__('Stores'));

        return $resultPage;
    }
}
