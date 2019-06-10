<?php

namespace Modzi\Pulseem\Helper;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Helper\AbstractHelper;

class Pulseem extends AbstractHelper
{
    protected $pulseem_wsdl_url = "http://www.pulseem.com/Pulseem/PulseemServices.asmx?WSDL";
    protected $_client;

    public function __construct()
    {
        $objectManager = ObjectManager::getInstance();
        $api_key = $objectManager
            ->get('Magento\Framework\App\Config\ScopeConfigInterface')
            ->getValue('pulseemconfig/general/api_key');
        if ($api_key) {
            $this->_client = new \SoapClient($this->pulseem_wsdl_url);
        }
    }

    public function getGroups()
    {
        $objectManager = ObjectManager::getInstance();
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $api_key = $objectManager
            ->get('Magento\Framework\App\Config\ScopeConfigInterface')
            ->getValue('pulseemconfig/general/api_key');
        $params = [
            'pwd' => $api_key,
            'GroupName' => ''
        ];

        try {
            $result = $this->_client->GetGroups($params);
            $xml = $result->GetGroupsResult->any;
            $xml = str_replace(array("diffgr:", "msdata:"), '', $xml);
            $xml = "<package>" . $xml . "</package>";
            $data = simplexml_load_string($xml);
            $groups = $data->diffgram->DocumentElement->Groups;
            $groups_array = [];
            foreach ($groups as $group) {
                array_push($groups_array, [
                    'value' => (string)$group->GroupID,
                    'label' => (string)$group->Name
                ]);
            }
            return $groups_array;
        } catch (\Exception $exception) {
            $logger->critical("PulseemGroups Pulseem service error: " . $exception->getMessage());
        }
    }

    public function postNewClient($groupID, $email, $firstName = '', $lastName = '', $birthday = '1900-01-01', $city = 'None', $address = 'None',
                                  $zip = 'None', $country = 'None', $state = 'None', $company = 'None', $telephone = '000-0000000', $cellphone = '000-0000000',
                                  $needOptin = false)
    {
        $objectManager = ObjectManager::getInstance();
        $logger = $objectManager->get('\Psr\Log\LoggerInterface');
        $api_key = $objectManager
            ->get('Magento\Framework\App\Config\ScopeConfigInterface')
            ->getValue('pulseemconfig/general/api_key');
        $params = array(
            'password' => $api_key,
            'intGroupID' => (int)$groupID,
            'strEmail' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'birthday' => $birthday,
            'city' => $city,
            'address' => $address,
            'zip' => $zip,
            'country' => $country,
            'state' => $state,
            'company' => $company,
            'telephone' => $telephone,
            'cellphone' => $cellphone,
            'needOptin' => (bool)$needOptin
        );
        try {
            $result = $this->_client->AddNewClient($params);
            return $result->AddNewClientResult;
        } catch (\Exception $exception) {
            $logger->critical("postNewClient Pulseem service error: " . $exception->getMessage());
        }
    }

}