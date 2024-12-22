<?php

namespace Maddlen\Nivo\Console\Command;

use Maddlen\Nivo\Services\Otp;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanExpiredOtp extends Command
{
    public function __construct(
        private readonly Otp $otpService,
        ?string              $name = null
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('nivo:otp:clean');
        $this->setDescription('Clean expired OTP requests');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->otpService->cleanExpired();
        return self::SUCCESS;
    }
}
