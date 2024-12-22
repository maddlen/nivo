<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Nivo\Controller\Customer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class Otp implements HttpGetActionInterface
{
    public function __construct(
        protected ResultFactory    $resultFactory,
        protected RequestInterface $request
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
        return $email
            ? $this->resultFactory->create(ResultFactory::TYPE_PAGE)
            : $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('customer/account/login');
    }
}
