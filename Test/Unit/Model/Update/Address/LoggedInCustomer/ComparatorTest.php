<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Test\Unit\Model\Update\Address\LoggedInCustomer;

use Generator;
use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Base\Test\Unit\Mock\TestObjectFactory;
use Klarna\Kec\Model\Update\Address\LoggedInCustomer\Comparator;
use Magento\Customer\Model\Data\Address;
use Magento\Customer\Model\Data\Region;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass  Klarna\Kec\Model\Update\Address\LoggedInCustomer\Comparator
 */
class ComparatorTest extends TestCase
{
    /**
     * @var Comparator
     */
    private Comparator $comparator;
    /**
     * @var Address
     */
    private Address $addressMock;
    /**
     * @var MockObject[]
     */
    private array $dependencyMocks;

    protected function setUp(): void
    {
        $mockFactory = new MockFactory($this);
        $objectFactory = new TestObjectFactory($mockFactory);

        $this->comparator = $objectFactory->create(Comparator::class);
        $this->addressMock = $mockFactory->create(Address::class);

        $this->dependencyMocks = $objectFactory->getDependencyMocks();

        $this->addressMock->method('getRegion')
            ->willReturn($mockFactory->create(Region::class));
    }

    /**
     * @dataProvider addressDataProvider
     */
    public function testIsApiAddressSameAsCustomerAddressReturnsTrue(
        array $addressData,
        array $normalizedKlarnaData
    ): void {
        $this->addressMock->method('__toArray')
            ->willReturn($addressData);

        $this->dependencyMocks['normalizer']->expects($this->once())
            ->method('normalizeShopAddress')
            ->willReturn($addressData);

        $result = $this->comparator->isApiAddressSameAsCustomerAddress($normalizedKlarnaData, $this->addressMock);
        static::assertSame(true, $result);
    }

    /**
     * @dataProvider addressDataProvider
     */
    public function testIsApiAddressSameAsCustomerAddressReturnsFalse(
        array $addressData,
        array $normalizedKlarnaData
    ): void {
        $normalizedKlarnaData['given_name'] = 'wrong name';

        $this->addressMock->method('__toArray')
            ->willReturn($addressData);

        $this->dependencyMocks['normalizer']->expects($this->once())
            ->method('normalizeShopAddress')
            ->willReturn($addressData);

        $result = $this->comparator->isApiAddressSameAsCustomerAddress($normalizedKlarnaData, $this->addressMock);
        static::assertSame(false, $result);
    }

    /**
     * @dataProvider addressDataProvider
     */
    public function testIsApiAddressSameAsCustomerAddressArrayReturnsFalse(
        array $addressData,
        array $normalizedKlarnaData
    ): void {
        $addressData['street'] = ['my street'];
        $normalizedKlarnaData['street_address'] = 'wrong street';

        $this->addressMock->method('__toArray')
            ->willReturn($addressData);

        $this->dependencyMocks['normalizer']->expects($this->once())
            ->method('normalizeShopAddress')
            ->willReturn($addressData);

        $result = $this->comparator->isApiAddressSameAsCustomerAddress($normalizedKlarnaData, $this->addressMock);
        static::assertSame(false, $result);
    }

    public function addressDataProvider(): Generator
    {
        $address = [
            'firstname' => 'the first name',
            'lastname' => 'the last name',
            'telephone' => '123456789',
            'street' => [
                'my street'
            ],
            'country_id' => 1,
            'city' => 'my city',
            'postcode' => '12345',
        ];

        yield [
            [
                'firstname' => $address['firstname'],
                'lastname' => $address['lastname'],
                'telephone' => $address['telephone'],
                'street' => $address['street'],
                'country_id' => $address['country_id'],
                'city' => $address['city'],
                'postcode' => $address['postcode'],
            ],
            [
                'given_name' => $address['firstname'],
                'family_name' => $address['lastname'],
                'phone' => $address['telephone'],
                'country' => $address['country_id'],
                'city' => $address['city'],
                'postal_code' => $address['postcode'],
                'street_address' => $address['street'][0],
            ]
        ];
    }
}