<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Nivo\Controller\Customer;

use Exception;
use Maddlen\Nivo\Services\Customer as CustomerService;
use Maddlen\Nivo\Services\Otp as OtpService;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\Manager;
use Psr\Log\LoggerInterface;

class OtpPost implements HttpPostActionInterface
{

    public function __construct(
        private readonly ResultFactory    $resultFactory,
        private readonly Session          $session,
        private readonly RequestInterface $request,
        private readonly OtpService       $otpService,
        private readonly Manager          $messageManager,
        private readonly LoggerInterface  $logger,
        private readonly CustomerService  $customerService

    )
    {
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $email = $this->request->getParam('email');
        $code = $this->request->getParam('code');
        try {
            $this->otpService->validate($email, $code);
            $this->customerService->login($email);
            $this->otpService->clearOne($email);
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage(), ['exception' => $e]);
            $this->messageManager->addErrorMessage(__('An error occurred while logging you in. Please make sure your code is correct or request a new one.'));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('nivo/customer/otp?email=' . $email);
        }
        $this->messageManager->addSuccessMessage(__('Your are now logged in.'));
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl($this->session->getBeforeAuthUrl());
    }
}
