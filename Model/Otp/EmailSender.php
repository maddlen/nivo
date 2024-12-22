<?php

namespace Maddlen\Nivo\Model\Otp;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

readonly class EmailSender
{
    public function __construct(
        private readonly ScopeConfigInterface  $scopeConfig,
        private readonly StoreManagerInterface $storeManager,
        private readonly StateInterface        $inlineTranslation,
        private readonly TransportBuilder      $transportBuilder,
    )
    {
    }

    public function sendEmail(Otp $otp): void
    {
        $template = $this->scopeConfig->getValue(Config::XML_PATH_OTP_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE, $this->getStoreId());
        $identity = $this->scopeConfig->getValue(Config::XML_PATH_OTP_EMAIL_IDENTITY, ScopeInterface::SCOPE_STORE, $this->getStoreId());
        if (!$template || !$identity) {
            return;
        }

        $templateVars = ['otp_code' => $otp->getCode()];
        $this->inlineTranslation->suspend();
        $this->transportBuilder
            ->setTemplateIdentifier($template)
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => $this->getStoreId(),
            ])
            ->setTemplateVars($templateVars)
            ->setFromByScope($identity, $this->getStoreId())
            ->addTo($otp->getEmail());
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();

        $this->inlineTranslation->resume();
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId(): int
    {
        return $this->storeManager->getStore()->getId();
    }
}
