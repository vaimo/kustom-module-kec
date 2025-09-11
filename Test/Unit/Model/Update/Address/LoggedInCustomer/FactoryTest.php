<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update\Address\LoggedInCustomer;

use Klarna\Kec\Model\Update\Address\LoggedInCustomer\Factory;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Customer\Model\Data\Address;
use Magento\Customer\Model\Data\Customer;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\Address\LoggedInCustomer\Factory
 */
class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private Factory $model;
    /**
     * @var Address|\PHPUnit\Framework\MockObject\MockObject
     */
    private Address $address;
    /**
     * @var Customer|\PHPUnit\Framework\MockObject\MockObject
     */
    private Customer $customer;

    /**
     * @dataProvider addressDataProvider
     */
    public function testCreateFromDataSettingTheCustomerId(array $addressData): void
    {
        $customerId = 1;
        $this->customer->method('getId')
            ->willReturn($customerId);
        $this->address->expects(static::once())
            ->method('setCustomerId')
            ->with($customerId);

        $this->model->createFromData(
            $addressData,
            $this->customer
        );
    }

    /**
     * @dataProvider addressDataProvider
     */
    public function testCreateFromDataReturningTheAddressInstance(array $addressData): void
    {
        $result = $this->model->createFromData(
            $addressData,
            $this->customer
        );

        static::assertInstanceof(Address::class, $result);
    }

    public function addressDataProvider()
    {
        return [
            [
                [
                    'given_name' => 'the first name',
                    'family_name' => 'the last name',
                    'phone' => '123456789',
                    'street_address' => [
                        'street1' => 'my street'
                    ],
                    'city' => 'my city',
                    'country' => 1,
                    'postal_code' => '12345'
                ]
            ]
        ];
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Factory::class);

        $this->address = $this->mockFactory->create(Address::class);
        $this->customer = $this->mockFactory->create(Customer::class);

        $this->dependencyMocks['magentoAddressFactory']->method('create')
            ->willReturn($this->address);
    }
}
