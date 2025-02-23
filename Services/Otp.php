<?php

namespace Maddlen\Nivo\Services;

use DateTime;
use Exception;
use Maddlen\Nivo\Model\Otp\Config;
use Maddlen\Nivo\Model\Otp\EmailSender;
use Maddlen\Nivo\Model\Otp\Otp as OtpModel;
use Maddlen\Nivo\Model\Otp\OtpFactory;
use Maddlen\Nivo\Model\Otp\ResourceModel\Otp as OtpResource;
use Maddlen\Nivo\Model\Otp\ResourceModel\Otp\CollectionFactory;
use Magento\Framework\Encryption\EncryptorInterface;

readonly class Otp
{

    public function __construct(
        private EncryptorInterface $encryptor,
        private OtpFactory         $otpFactory,
        private OtpResource        $otpResource,
        private CollectionFactory  $otpCollectionFactory,
        private EmailSender        $emailSender,
    )
    {
    }

    public function sendEmail(OtpModel $otp): void
    {
        $this->emailSender->sendEmail($otp);
    }

    public function cleanExpired(): void
    {
        $timeLimit = (new DateTime())->modify("-" . Config::RESET_DELAY . " minutes")->format('Y-m-d H:i:s');
        $collection = $this->otpCollectionFactory->create();
        $collection->addFieldToFilter('created_at', ['lt' => $timeLimit]);
        $collection->walk('delete');
    }

    public function create(string $email): OtpModel
    {
        $code = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_LEFT); // 6 digits without leading 0
        $hash = $this->encryptor->encrypt(implode(Config::HASH_SEPARATOR, [$email, $code]));
        $otp = $this->otpFactory->create();
        $otp->setData(['email' => $email, 'hash' => $hash]);
        $this->otpResource->securelySave($otp);
        $otp->setData('code', $code);
        return $otp;
    }

    public function validate(string $email, string $code): void
    {
        $otp = $this->otpFactory->create();
        $this->otpResource->loadByEmail($otp, $email);
        [$otpEmail, $otpCode] = explode(Config::HASH_SEPARATOR, $this->encryptor->decrypt($otp->getHash()));
        if ($code !== $otpCode || $email !== $otpEmail) {
            throw new Exception(__('OTP code and/or email does not match.'));
        }
    }

    public function clearOne(string $email): void
    {
        $otp = $this->otpFactory->create();
        $this->otpResource->loadByEmail($otp, $email);
        $this->otpResource->delete($otp);
    }
}
