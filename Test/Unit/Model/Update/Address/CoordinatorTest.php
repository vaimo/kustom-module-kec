<?php

/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update\Address;

use Klarna\Base\Exception as KlarnaException;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Klarna\Kec\Model\Update\Address\Coordinator;
use Magento\Customer\Model\Data\Address;
use Magento\Customer\Model\Data\Customer;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address as QuoteAddress;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\Address\Coordinator
 */
class CoordinatorTest extends TestCase
{
    /**
     * @var Coordinator
     */
    private Coordinator $coordinator;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;

    public function testUpdateAddressForGuestIsSuccess(): void
    {
        $shippingAddressData = ['shipping_address' => ['street' => '123 Main St', 'city' => 'Anytown']];

        $billingAddress = $this->createMock(QuoteAddress::class);
        $billingAddress->method('getStreet')->willReturn('123 Main St');
        $billingAddress->method('getCity')->willReturn('Anytown');

        $this->magentoQuote
            ->method('getCustomerIsGuest')
            ->willReturn(true);

        $this->magentoQuote
            ->method('getBillingAddress')
            ->willReturn($billingAddress);

        $this->dependencyMocks['normalizer']->method('normalizeKlarnaAddress')
            ->willReturn($shippingAddressData['shipping_address']);

        $this->dependencyMocks['guest']
            ->expects($this->once())
            ->method('applyKlarnaAddressData')
            ->with($shippingAddressData, $this->magentoQuote);

        $this->coordinator->updateAddress($shippingAddressData, $this->magentoQuote);

        $this->assertEquals('123 Main St', $billingAddress->getStreet());
        $this->assertEquals('Anytown', $billingAddress->getCity());
    }

    public function testUpdateAddressForLoggedInSuccess(): void
    {
        $address = $this->mockFactory->create(Address::class);
        $quoteAddress = $this->mockFactory->create(QuoteAddress::class);
        $customer = $this->mockFactory->create(Customer::class);

        $this->magentoQuote
            ->method('getCustomer')
            ->willReturn($customer);

        $this->dependencyMocks['fetcher']
            ->method('getAddressFromCustomerOrCreate')
            ->willReturn($address);

        $this->magentoQuote
            ->method('getIsVirtual')
            ->willReturn(true);

        $this->magentoQuote
            ->method('getBillingAddress')
            ->willReturn($quoteAddress);

        $quoteAddress
            ->expects($this->atLeastOnce())
            ->method('importCustomerAddressData')
            ->with($address)
            ->willReturnSelf();

        $result = $this->coordinator->updateAddress(['shipping_address' => []], $this->magentoQuote);

        static::assertNull($result);
        static::assertSame($quoteAddress, $this->magentoQuote->getBillingAddress());

        $this->magentoQuote
            ->method('getIsVirtual')
            ->willReturn(false);

        $this->magentoQuote
            ->method('getShippingAddress')
            ->willReturn($quoteAddress);

        $result = $this->coordinator->updateAddress(['shipping_address' => []], $this->magentoQuote);

        static::assertNull($result);
        static::assertSame($quoteAddress, $this->magentoQuote->getShippingAddress());
    }

    public function testUpdateAddressWithIncompleteAddressArray(): void
    {
        $this->magentoQuote
            ->method('getCustomerIsGuest')
            ->willReturn(true);

        $this->dependencyMocks['guest']
            ->expects($this->never())
            ->method('applyKlarnaAddressData');

        $result = $this->coordinator->updateAddress(['incomplete' => 'data'], $this->magentoQuote);

        static::assertNull($result);

        $this->magentoQuote
            ->method('getCustomerIsGuest')
            ->willReturn(false);

        $result = $this->coordinator->updateAddress(['incomplete' => 'data'], $this->magentoQuote);

        static::assertNull($result);
    }

    public function testUpdateAddressWithMissingCustomerData(): void
    {
        $this->magentoQuote
            ->method('getCustomer')
            ->willReturn(null);

        $this->dependencyMocks['fetcher']
            ->expects($this->never())
            ->method('getAddressFromCustomerOrCreate');

        self::expectException(\TypeError::class);

        $this->coordinator->updateAddress(['shipping_address' => []], $this->magentoQuote);
    }

    public function testUpdateAddressWithMissingCustomerDataThrowsException(): void
    {
        $customer = $this->mockFactory->create(Customer::class);

        $this->magentoQuote
            ->method('getCustomer')
            ->willReturn($customer);

        $this->dependencyMocks['fetcher']
            ->method('getAddressFromCustomerOrCreate')
            ->willThrowException(new KlarnaException(__('')));

        self::expectException(KlarnaException::class);

        $this->coordinator->updateAddress(['shipping_address' => []], $this->magentoQuote);
    }

    public function testUpdateAddressWithInvalidShippingDetails(): void
    {
        $address = $this->mockFactory->create(Address::class);
        $quoteAddress = $this->mockFactory->create(QuoteAddress::class);
        $customer = $this->mockFactory->create(Customer::class);

        $this->magentoQuote
            ->method('getCustomer')
            ->willReturn($customer);

        $this->dependencyMocks['fetcher']
            ->method('getAddressFromCustomerOrCreate')
            ->willReturn($address);

        $this->magentoQuote
            ->method('getIsVirtual')
            ->willReturn(true);

        $this->magentoQuote
            ->method('getBillingAddress')
            ->willReturn(null);

        $quoteAddress
            ->expects($this->never())
            ->method('importCustomerAddressData');

        $initialQuoteState = clone $this->magentoQuote;

        $this->coordinator->updateAddress(['shipping_address' => []], $this->magentoQuote);

        self::assertEquals($initialQuoteState, $this->magentoQuote);
    }

    protected function setUp(): void
    {
        $this->coordinator = parent::setUpMocks(Coordinator::class);

        $this->magentoQuote = $this->mockFactory->create(Quote::class);
    }
}