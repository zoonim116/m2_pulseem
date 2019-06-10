<?php

namespace Modzi\Pulseem\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modzi\Pulseem\Helper\Data;
use Modzi\Pulseem\Helper\Pulseem;
use Magento\Sales\Model\Order;

class PulseemSubmitCheckoutData implements ObserverInterface
{
    protected $_pulseemSettings;
    protected $_pulseem;
    protected $_order;

    public function __construct(
        Data $data,
        Pulseem $pulseem,
        Order $order
    )
    {
        $this->_pulseemSettings = $data;
        $this->_pulseem = $pulseem;
        $this->_order = $order;
    }

    public function execute(Observer $observer)
    {
        if ($this->_pulseemSettings->getIsUserPurchased()) {
            $order = $observer->getEvent()->getData('order');
            $userPurchaseGroupId = $this->_pulseemSettings->getUserPurchaseGroupId();
            $this->_pulseem->postNewClient($userPurchaseGroupId,
                $order->getCustomerEmail(),
                $order->getCustomerFirstname(),
                $order->getCustomerLastname()
            );
        }
    }
}