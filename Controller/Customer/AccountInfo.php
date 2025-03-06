<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Nivo\Controller\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Reflection\DataObjectProcessor;

class AccountInfo implements HttpPostActionInterface
{
    public function __construct(
        private readonly ResultFactory               $resultFactory,
        private readonly Session                     $session,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly DataObjectProcessor         $dataObjectProcessor
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
        $customer = $this->customerRepository->getById($this->session->getCustomer()->getId());
        $customerData = $this->dataObjectProcessor->buildOutputDataArray($customer, CustomerInterface::class);
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData(['customer' => $customerData]);
    }
}
