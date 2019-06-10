<?php

namespace Modzi\Pulseem\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\App\ObjectManager;

class Group implements ArrayInterface
{
    public function toOptionArray()
    {
        $objectManager = ObjectManager::getInstance();
        return $objectManager->get('Modzi\Pulseem\Helper\Pulseem')->getGroups();
    }
}