<?php
namespace P2c2p\P2c2pPayment\Model;
use Magento\Framework\Model\AbstractModel;

class Token extends AbstractModel
{
	protected function _construct()
	{
		$this->_init('P2c2p\P2c2pPayment\Model\ResourceModel\Token');
	}
}