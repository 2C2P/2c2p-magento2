<?php

/*
 * Created by Aloha
 * Date 19 June 2017
 * P2c2pMeta helper class is used to store data into table. i.e created by P2c2p plugin.
 */

namespace P2c2p\P2c2pPayment\Helper;

use P2c2p\P2c2pPayment\Model\TokenFactory;
use P2c2p\P2c2pPayment\Model\MetaFactory;

class P2c2pMeta
{	
	protected $objTokenFactory, $objMetaFactory;

	public function __construct(TokenFactory $objTokenFactory, MetaFactory $objMetaFactory) {
		$this->objTokenFactory = $objTokenFactory;
		$this->objMetaFactory  = $objMetaFactory;
	}

	//Save the Payment getaway response into p2c2p_meta table using the current payment order_id.
	public function savePaymentGetawayResponse($request,$user_id) {

		$model = $this->objMetaFactory->create();	

		$model->setData('p2c2p_id' , $request['order_id']);
		$model->setData('order_id' , $request['order_id']);
		$model->setData('user_id' , $user_id);
		$model->setData('version' , $request['version']);
		$model->setData('request_timestamp' , $request['request_timestamp']);
		$model->setData('merchant_id' , $request['merchant_id']);
		$model->setData('invoice_no' , $request['invoice_no']);
		$model->setData('currency' , $request['currency']);
		$model->setData('amount' , $request['amount']);
		$model->setData('transaction_ref' , $request['transaction_ref']);
		$model->setData('approval_code' , $request['approval_code']);
		$model->setData('eci' , $request['eci']);
		$model->setData('transaction_datetime' , $request['transaction_datetime']);
		$model->setData('payment_channel' , $request['payment_channel']);
		$model->setData('payment_status' , $request['payment_status']);
		$model->setData('channel_response_code' , $request['channel_response_code']);
		$model->setData('channel_response_desc' , $request['channel_response_desc']);
		$model->setData('masked_pan' , $request['masked_pan']);
		$model->setData('stored_card_unique_id' , $request['stored_card_unique_id']);
		$model->setData('backend_invoice' , $request['backend_invoice']);
		$model->setData('paid_channel' , $request['paid_channel']);
		$model->setData('paid_agent' , $request['paid_agent']);
		$model->setData('recurring_unique_id' , $request['recurring_unique_id']);
		$model->setData('user_defined_1' , $request['user_defined_1']);
		$model->setData('user_defined_2' , $request['user_defined_2']);
		$model->setData('user_defined_3' , $request['user_defined_3']);
		$model->setData('user_defined_4' , $request['user_defined_4']);
		$model->setData('user_defined_5' , $request['user_defined_5']);
		$model->setData('browser_info' , $request['browser_info']);
		$model->setData('ippPeriod' , $request['ippPeriod']);
		$model->setData('ippInterestType' , $request['ippInterestType']);
		$model->setData('ippInterestRate' , $request['ippInterestRate']);
		$model->setData('ippMerchantAbsorbRate' , $request['ippMerchantAbsorbRate']);
		$model->save();
	}

	// save the logged in user token into p2c2p_token table for stored card payment getaway.
	public function saveUserToken($arrayTokenData) {
		$model = $this->objTokenFactory->create();

		$model->setData($arrayTokenData);
		$model->save();
	}

	// get the logged in customer token data from p2c2p_token table.
	public function getUserToken($intUserId) {
		if(empty($intUserId)) 
			return;

		$objTokenFactoryModel = $this->objTokenFactory->create()->getCollection()->addFieldToFilter('user_id',$intUserId);

		return $objTokenFactoryModel;
	}

	// get the token detail passing the token key
	public function getTokenByTokenId($intTokenId) {
		if(!isset($intTokenId))
			return;

		return $this->objTokenFactory->create()->load($intTokenId);	
	}
}