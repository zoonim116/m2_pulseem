<?php

namespace Modzi\Pulseem\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modzi\Pulseem\Helper\Data;
use Modzi\Pulseem\Helper\Pulseem;

class PulseemAddToCartItem implements ObserverInterface
{

    protected $_pulseemSettings;
    protected $_pulseem;
    protected $_objectManager;

    public function __construct(
        Data $data,
        Pulseem $pulseem
    )
    {
        $this->_pulseemSettings = $data;
        $this->_pulseem = $pulseem;
    }

    public function execute(Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        $checkoutSession = $objectManager->get('Magento\Checkout\Model\Session');
        echo "<pre>";
        die(var_dump($checkoutSession->getQuoteId()));
        if($customerSession->isLoggedIn()) {
            die(var_dump($customerSession->getCustomer()->getEmail()));
        } else {

        }
        if ($this->_pulseemSettings->getIsUserRegister()) {
            $customer = $observer->getEvent()->getCustomer();
            $userRegisterGroupId = $this->_pulseemSettings->getUserRegisterGroupId();
            $this->_pulseem->postNewClient($userRegisterGroupId, $customer->getEmail(), $customer->getFirstname(), $customer->getLastname());
        }
    }
}