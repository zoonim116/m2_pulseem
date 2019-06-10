<?php

namespace Modzi\Pulseem\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modzi\Pulseem\Helper\Data;
use Modzi\Pulseem\Helper\Pulseem;

class PulseemSubmitRegistrationData implements ObserverInterface
{
    protected $_pulseemSettings;
    protected $_pulseem;

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
        if ($this->_pulseemSettings->getIsUserRegister()) {
            $customer = $observer->getEvent()->getCustomer();
            $userRegisterGroupId = $this->_pulseemSettings->getUserRegisterGroupId();
            $this->_pulseem->postNewClient($userRegisterGroupId, $customer->getEmail(), $customer->getFirstname(), $customer->getLastname());
        }
    }
}