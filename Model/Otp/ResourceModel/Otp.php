<?php

namespace Maddlen\Nivo\Model\Otp\ResourceModel;

use Exception;
use Maddlen\Nivo\Model\Otp\Config;
use Maddlen\Nivo\Model\Otp\Otp as OtpModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Otp extends AbstractDb
{

    /**
     * @var string
     */
    protected $_eventPrefix = 'maddlen_nivo_otp_resource_model';

    public function securelySave(OtpModel $otp): void
    {
        $hash = $otp->getHash();
        $this->loadByEmail($otp, $otp->getEmail());
        if (!$otp->getId()) {
            $otp->setData('count', 1);
            $this->save($otp);
            return;
        }
        if ($otp->getCount() >= Config::MAX_SUBMISSION_COUNT) {
            throw new Exception(__('Maximum number of submissions reached. Please try again in %1 minutes.', Config::RESET_DELAY));
        }
        $otp->setData('hash', $hash);
        $otp->setData('count', $otp->getCount() + 1);
        $this->save($otp);
    }

    public function loadByEmail(OtpModel $otp, string $email): void
    {
        $this->load($otp, $email, 'email');
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('maddlen_nivo_otp', 'entity_id');
        $this->_useIsObjectNew = true;
    }


}
