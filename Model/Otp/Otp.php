<?php

namespace Maddlen\Nivo\Model\Otp;

use Maddlen\Nivo\Model\Otp\ResourceModel\Otp as ResourceModel;
use Magento\Framework\Model\AbstractModel;

/**
 * @method string getEmail()
 * @method string getHash()
 * @method int getCode()
 * @method int getCount()
 */
class Otp extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'maddlen_nivo_otp_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
