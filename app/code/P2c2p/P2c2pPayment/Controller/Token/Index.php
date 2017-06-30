<?php

namespace P2c2p\P2c2pPayment\Controller\Token;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use P2c2p\P2c2pPayment\Model\TokenFactory;

class Index extends \Magento\Framework\App\Action\Action
{    
    protected $objTokenFactory;

    public function __construct(Context $context,TokenFactory $objTokenFactory)
    {
        parent::__construct($context);             
        $this->objTokenFactory    = $objTokenFactory;
    }

    public function execute()
    {
        if(!isset($_POST['userId']) || empty($_POST['userId']))
            return;

        $objTokenFactoryModel = $this->objTokenFactory->create();
        $tokenCollection =  $objTokenFactoryModel->getCollection()->addFieldToFilter('user_id',$_POST['userId']);

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);    

        if(count($tokenCollection) > 0){
            $resultJson->setData($tokenCollection);
        }else{
            $resultJson->setData(count($tokenCollection));
        }
        
        return $resultJson;
    }
}