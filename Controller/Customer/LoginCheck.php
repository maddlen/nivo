<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Nivo\Controller\Customer;

use Maddlen\Nivo\Services\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;

class LoginCheck implements HttpPostActionInterface
{
    public function __construct(
        protected ResultFactory $resultFactory,
        protected Session       $customerSession
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
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData([Customer::LOGGED_IN_COOKIE => $this->customerSession->isLoggedIn()]);
        return $result;
    }
}
