<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update\Address\LoggedInCustomer;

use Klarna\Kec\Model\Update\Address\LoggedInCustomer\Repository;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Model\Data\Address;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\Address\LoggedInCustomer\Repository
 */
class RepositoryTest extends TestCase
{
    /**
     * @var Repository
     */
    private Repository $model;
    /**
     * @var Address|\PHPUnit\Framework\MockObject\MockObject
     */
    private Address $address;
    /**
     * @var Customer|\PHPUnit\Framework\MockObject\MockObject
     */
    private Customer $customer;

    public function testCreateEntrySaveCreatedAddressInstance(): void
    {
        $this->dependencyMocks['factory']->method('createFromData')
            ->willReturn($this->address);
        $this->dependencyMocks['addressRepository']->expects(static::once())
            ->method('save')
            ->with($this->address)
            ->willReturn($this->address);

        $result = $this->model->createEntry([], $this->customer);
        static::assertSame($this->address, $result);
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Repository::class);

        $this->address = $this->mockFactory->create(Address::class);
        $this->customer = $this->mockFactory->create(Customer::class);
    }
}
