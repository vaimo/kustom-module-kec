<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Model\Update\Address\LoggedInCustomer;

use Klarna\Base\Exception as KlarnaException;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * @internal
 */
class Finder
{
    /**
     * @var Comparator
     */
    private Comparator $comparator;

    /**
     * @param Comparator $comparator
     * @codeCoverageIgnore
     */
    public function __construct(Comparator $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * Finding a address in the customer address book and returning it or throwing a exeption
     *
     * @param array $klarnaAddressData
     * @param CustomerInterface $customer
     * @return AddressInterface
     * @throws KlarnaException
     */
    public function findCustomerAddress(array $klarnaAddressData, CustomerInterface $customer): AddressInterface
    {
        foreach ($customer->getAddresses() as $address) {
            if ($this->comparator->isApiAddressSameAsCustomerAddress($klarnaAddressData, $address)) {
                return $address;
            }
        }

        throw new KlarnaException(__('No address found'));
    }
}
