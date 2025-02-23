<?php

namespace Maddlen\Nivo\Controller\Customer;

use Exception;
use Maddlen\Nivo\Services\Otp as OtpService;
use Maddlen\ZermattForm\FormRules\FormRulesActionInterface;
use Maddlen\ZermattForm\FormRules\FormRulesService;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Framework\Validator\EmailAddress;
use Magento\Framework\Validator\NotEmpty;
use Psr\Log\LoggerInterface;

class LoginPost implements FormRulesActionInterface
{
    private bool $isSuccess = true;

    public function __construct(
        private readonly FormRulesService $formRulesService,
        private readonly RequestInterface $request,
        private readonly UrlInterface     $url,
        private readonly OtpService       $otpService,
        private readonly LoggerInterface  $logger,
    )
    {
    }

    public function execute()
    {
        return $this->formRulesService->execute($this);
    }

    public function rules(): array
    {
        return [
            'email' => [
                NotEmpty::class,
                EmailAddress::class
            ]
        ];
    }

    public function redirectUrl(): string
    {
        return $this->isSuccess ?
            $this->url->getUrl('nivo/customer/otp') . sprintf('?email=%s', $this->request->getParam('email'))
            : $this->url->getUrl('customer/account/login');
    }

    public function getSuccessMessage(): ?Phrase
    {
        return __('An email with a unique login code was sent to your email address.');
    }

    public function submitForm(): bool
    {
        try {
            $otp = $this->otpService->create($this->request->getParam('email'));
            $this->otpService->sendEmail($otp);
            return true;
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage(), ['exception' => $e]);
            $this->isSuccess = false;
            throw $e;
        }
    }
}
