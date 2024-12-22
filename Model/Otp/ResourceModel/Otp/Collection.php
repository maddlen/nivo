<?php

namespace Maddlen\Nivo\Model\Otp\ResourceModel\Otp;

use Maddlen\Nivo\Model\Otp\Otp as Model;
use Maddlen\Nivo\Model\Otp\ResourceModel\Otp as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'maddlen_nivo_otp_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
