<?php

namespace Modzi\Pulseem\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class Data extends AbstractHelper
{
    protected $encryptor;

    public function __construct(
        Context $context,
        EncryptorInterface $encryptor
    )
    {
        parent::__construct($context);
        $this->encryptor = $encryptor;
    }

    public function getApiKey($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('pulseemconfig/general/api_key', $scope);
    }

    public function getUserPurchaseGroupId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('pulseemconfig/general/add_user_after_purchase_category', $scope);
    }

    public function getUserRegisterGroupId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue('pulseemconfig/general/add_new_user_category', $scope);
    }

    public function getIsUserPurchased($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->isSetFlag('pulseemconfig/general/add_user_after_purchase', $scope);
    }

    public function getIsUserRegister($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->isSetFlag('pulseemconfig/general/add_new_user', $scope);
    }
}