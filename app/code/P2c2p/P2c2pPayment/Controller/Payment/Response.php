<?php
/*
 * Created by Aloha
 * Date 20 June 2017
 * This Response action method is responsible for handle the 2c2p payment gateway response.
 */

namespace P2c2p\P2c2pPayment\Controller\Payment;

class Response extends \P2c2p\P2c2pPayment\Controller\AbstractCheckoutRedirectAction
{
	public function execute()
	{		
		//If payment getway response is empty then redirect to home page directory.		
		if(empty($_REQUEST)){
			$this->_redirect('');
			return;
		}

		$hashHelper   = $this->getHashHelper();
		$configHelper = $this->getConfigSettings();
		$objCustomerData = $this->getCustomerSession();
		$isValidHash  = $hashHelper->isValidHashValue($_REQUEST,$configHelper['secretKey']);

		//Check whether hash value is valid or not If not valid then redirect to home page when hash value is wrong.
		if(!$isValidHash) {
			$this->_redirect('');
			return;
		}

		//Get Payment getway response to variable.
		$payment_status_code = $_REQUEST['payment_status'];
		$transaction_ref 	 = $_REQUEST['transaction_ref']; 
		$approval_code   	 = $_REQUEST['approval_code'];
		$payment_status  	 = $_REQUEST['payment_status'];
		$order_id 		 	 = $_REQUEST['order_id'];

		//Get the object of current order.
		$order = $this->getOrder()->load($order_id);

		$metaDataHelper = $this->getMetaDataHelper();		
		$metaDataHelper->savePaymentGetawayResponse($_REQUEST,$order->getCustomerId());

		//check payment status according to payment response.
		if(strcasecmp($payment_status_code, "000") == 0) {			
			//IF payment status code is success

			if($objCustomerData->isLoggedIn() && !empty($_REQUEST['stored_card_unique_id'])) {
				$intCustomerId = $objCustomerData->getCustomerId();
				$boolIsFound = false;

				// Fetch data from database by using the customer ID.
				$objTokenData = $metaDataHelper->getUserToken($intCustomerId);
				
				$arrayTokenData = array('user_id' => $intCustomerId,
					'stored_card_unique_id' => $_REQUEST['stored_card_unique_id'],
					'masked_pan' => $_REQUEST['masked_pan'],
					'created_time' =>  date("Y-m-d H:i:s"));

				/* 
				   Iterate foreach and check whether token key is present into p2c2p_token table or not.
				   If token key is already present into database then prevent insert entry otherwise insert token entry into database.
				*/				   
				foreach ($objTokenData as $key => $value) {
					if(strcasecmp($value->getData('masked_pan'), $_REQUEST['masked_pan']) == 0 && 
					   strcasecmp($value->getData('stored_card_unique_id'), $_REQUEST['stored_card_unique_id']) == 0) {
						$boolIsFound = true;
						break;
					}
				}

				if(!$boolIsFound) {
					$metaDataHelper->saveUserToken($arrayTokenData);					
				}
			}

			//Set the complete status when payment is completed.
			$order->setState(\Magento\Sales\Model\Order::STATE_COMPLETE);
			$order->setStatus(\Magento\Sales\Model\Order::STATE_COMPLETE);
			$order->save();				

			$this->executeSuccessAction($_REQUEST);
			return;

		} else if(strcasecmp($payment_status_code, "001") == 0) {			
			//Set the Pending payment status when payment is pending. like 123 payment type.
			$order->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
			$order->setStatus(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT);
			$order->save();

			$this->executeSuccessAction($_REQUEST);
			return;

		} else {
			//If payment status code is cancel/Error/other.
			$order->setState(\Magento\Sales\Model\Order::STATE_CANCELED);
			$order->setStatus(\Magento\Sales\Model\Order::STATE_CANCELED);
			$order->save();

			$this->executeCancelAction();
			return;
		}
	}
}