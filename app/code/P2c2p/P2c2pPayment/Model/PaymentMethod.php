<?php

/*
 * Created by 2C2P
 * Date 19 June 2017
 * PaymentMethod is base class / entry point for 2c2p plugin.
 */

namespace P2c2p\P2c2pPayment\Model;

class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{	
	protected $_code = 'p2c2ppayment';
	protected $_isInitializeNeeded = true;
    protected $_canUseInternal = true;
    protected $_canUseCheckout = true;
    protected $_canUseForMultishipping = false;

    //protected $_formBlockType = 'P2c2p\P2c2pPayment\Block\Form';

    //Set additional data and session object and use it further process.
	public function assignData(\Magento\Framework\DataObject $data)
	{    	
		parent::assignData($data);

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$catalogSession = $objectManager->create('\Magento\Catalog\Model\Session');

		if(isset($data)) {
			if(!empty($data->getData()['additional_data'])) {
				$catalogSession->setTokenValue($data->getData()['additional_data']['test1']);
			}
		}

        return $this;
    }

    /**
     * Instantiate state and set it to state object
     * @param string $paymentAction
     * @param Varien_Object
     */
    public function initialize($paymentAction, $stateObject)
    {        
        $stateObject->setState("Pending_2C2P");
        $stateObject->setStatus("Pending_2C2P");
        $stateObject->setIsNotified(false); 
    }
}