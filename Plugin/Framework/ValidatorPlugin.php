<?php

namespace Maddlen\Nivo\Plugin\Framework;

use Maddlen\Nivo\Model\Customer\ValidatorFlag;
use Magento\Framework\Validator;

class ValidatorPlugin
{
    public function __construct(
        private readonly ValidatorFlag $validatorFlag,
    )
    {
    }

    /**
     * @param Validator $subject
     * @param bool $result
     * @param mixed $value
     * @return bool
     */
    public function afterIsValid(Validator $subject, bool $result, $value): bool
    {
        return $this->validatorFlag->isBypassed() ? true : $result;
    }
}
