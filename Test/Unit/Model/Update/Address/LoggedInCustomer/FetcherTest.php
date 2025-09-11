<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update\Address\LoggedInCustomer;

use Klarna\Base\Exception as KlarnaException;
use Klarna\Kec\Model\Update\Address\LoggedInCustomer\Fetcher;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Customer\Model\Data\Address;
use Magento\Customer\Model\Data\Customer;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\Address\LoggedInCustomer\Fetcher
 */
class FetcherTest extends TestCase
{
    /**
     * @var Fetcher
     */
    private Fetcher $model;
    /**
     * @var Address|\PHPUnit\Framework\MockObject\MockObject
     */
    private Address $address;
    /**
     * @var Customer|\PHPUnit\Framework\MockObject\MockObject
     */
    private Customer $customer;

    public function testGetAddressFromCustomerOrCreateReturnsFoundAddress(): void
    {
        $this->dependencyMocks['finder']->method('findCustomerAddress')
            ->willReturn($this->address);
        $this->dependencyMocks['repository']->expects(static::never())
            ->method('createEntry');
        $result = $this->model->getAddressFromCustomerOrCreate([], $this->customer);

        static::assertSame($this->address, $result);
    }

    public function testGetAddressFromCustomerOrCreateNoAddressWasFound(): void
    {
        $this->dependencyMocks['finder']->method('findCustomerAddress')
            ->willThrowException(new KlarnaException(__()));
        $this->dependencyMocks['repository']->expects(static::once())
            ->method('createEntry')
            ->willReturn($this->address);
        $result = $this->model->getAddressFromCustomerOrCreate([], $this->customer);

        static::assertSame($this->address, $result);
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Fetcher::class);

        $this->address = $this->mockFactory->create(Address::class);
        $this->customer = $this->mockFactory->create(Customer::class);
    }
}
