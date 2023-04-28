<?php
/**
 * Copyright © 2018 Acme. All rights reserved.
 * @license GPLv3
 */

namespace Acme\StoreLocator\Model\System\Config;

use \Magento\Framework\Option\ArrayInterface;
use \Acme\StoreLocator\Model\Source\IsActive as Source;

class IsActive implements ArrayInterface
{
    /**
     * @var \Acme\StoreLocator\Model\Source\IsActive
     */
    private $source;

    /**
     * @param \Acme\StoreLocator\Model\Source\IsActive $source
     */
    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return $this->source->getAvailableStatuses();
    }
}
