<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Nivo\Plugin\Customer\Controller\Account;

use Magento\Customer\Controller\Account\Login;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Url;

class LoginPlugin
{
    public function __construct(
        protected Session $session,
        protected RedirectInterface $redirect,
        protected Url $urlBuilder
    )
    {
    }

    /**
     * @param Login $subject
     * @return array
     */
    public function beforeExecute(Login $subject): array
    {
        $referer = $this->redirect->getRedirectUrl();
        $beforeAuthUrl = str_contains($referer, '/customer/') ? $this->urlBuilder->getUrl() : $referer;
        $this->session->setBeforeAuthUrl($beforeAuthUrl);
        return [];
    }
}
