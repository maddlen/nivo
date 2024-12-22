<?php

namespace Maddlen\Nivo\Model\Customer;

class ValidatorFlag
{
    public function __construct(
        private bool $bypass = false
    )
    {
    }

    public function isBypassed(): bool
    {
        return $this->bypass;
    }

    public function setBypass(bool $bypass): void
    {
        $this->bypass = $bypass;
    }

}
