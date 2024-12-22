<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Maddlen\Nivo\Services;

use Maddlen\Nivo\Model\Customer\ValidatorFlag;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Customer
{
    const LOGGED_IN_COOKIE = 'logged_in';

    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly Session                     $session,
        private readonly CookieManagerInterface      $cookieManager,
        private readonly CookieMetadataFactory       $cookieMetadataFactory,
        private readonly CustomerInterfaceFactory    $customerDataFactory,
        private readonly ValidatorFlag               $validatorFlag,
    )
    {
    }

    public function login(string $email): void
    {
        try {
            $customer = $this->customerRepository->get($email);
        } catch (NoSuchEntityException $e) {
            $customer = $this->customerDataFactory->create();
            $customer->setEmail($email);
            $this->validatorFlag->setBypass(true);
            $customer = $this->customerRepository->save($customer);
            $this->validatorFlag->setBypass(false);
        }
        $this->session->setCustomerDataAsLoggedIn($customer);
        $metadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
        $metadata->setPath('/');
        $this->cookieManager->setPublicCookie(Customer::LOGGED_IN_COOKIE, 'true', $metadata);
    }
}
