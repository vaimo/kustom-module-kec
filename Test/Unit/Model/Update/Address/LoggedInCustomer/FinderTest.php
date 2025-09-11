<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update\Address\LoggedInCustomer;

use Klarna\Base\Exception as KlarnaException;
use Klarna\Kec\Model\Update\Address\LoggedInCustomer\Finder;
use Magento\Customer\Model\Data\Customer;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Customer\Model\Data\Address;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\Address\LoggedInCustomer\Finder
 */
class FinderTest extends TestCase
{
    /**
     * @var Finder
     */
    private Finder $model;
    /**
     * @var Customer|\PHPUnit\Framework\MockObject\MockObject
     */
    private Customer $customer;
    /**
     * @var Address|\PHPUnit\Framework\MockObject\MockObject
     */
    private Address $address;

    public function testFindCustomerAddressCustomerHasNoAddressInAddressBook(): void
    {
        $this->customer->method('getAddresses')
            ->willReturn([]);

        $this->expectException(KlarnaException::class);
        $this->expectExceptionMessage("No address found");
        $this->model->findCustomerAddress([], $this->customer);
    }

    public function testFindCustomerAddressNoAddressWasFound(): void
    {
        $addresses = [
            $this->address
        ];
        $this->customer->method('getAddresses')
            ->willReturn($addresses);

        $this->expectException(KlarnaException::class);
        $this->expectExceptionMessage("No address found");
        $this->model->findCustomerAddress([], $this->customer);
    }

    public function testFindCustomerAddressFoundAddress(): void
    {
        $addresses = [
            $this->address
        ];
        $this->customer->method('getAddresses')
            ->willReturn($addresses);

        $this->dependencyMocks['comparator']->method('isApiAddressSameAsCustomerAddress')
            ->willReturn(true);
        $result = $this->model->findCustomerAddress([], $this->customer);
        static::assertSame($this->address, $result);
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Finder::class);

        $this->customer = $this->mockFactory->create(Customer::class);
        $this->address = $this->mockFactory->create(Address::class);
    }
}
