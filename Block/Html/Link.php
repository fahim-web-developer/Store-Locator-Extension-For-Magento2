<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Block\Html;

class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * {@inheritdoc}
     */
    public function getHref()
    {
        return $this->_urlBuilder->getUrl('storelocator');
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        return $this->getData('current') || $this->_urlBuilder->getCurrentUrl() === $this->getHref();
    }
}
