<?php

namespace Maddlen\Nivo\Model\Otp;

class Config
{
    public const MAX_SUBMISSION_COUNT = 5;
    public const RESET_DELAY = 15; // Minutes
    public const HASH_SEPARATOR = '|';
    public const XML_PATH_OTP_EMAIL_TEMPLATE = 'maddlen_nivo/otp/otp_email_template';
    public const XML_PATH_OTP_EMAIL_IDENTITY = 'customer/create_account/email_identity';
}
