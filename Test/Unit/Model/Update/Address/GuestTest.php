<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update;

use Klarna\Kec\Model\Update\Address\Guest;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Magento\Quote\Model\Quote;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\Address\Guest
 */
class GuestTest extends TestCase
{
    /**
     * @var Guest
     */
    private Guest $guest;
    /**
     * @var QuoteAddress
     */
    private QuoteAddress $quoteAddress;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;

    public function testApplyKlarnaAddressDataSettingBillingShippingAddress(): void
    {
        $input = [
            'shipping_address' => [
                'a' => 'b',
                'c' => 'd',
                'email' => 'f'
            ]
        ];
        $this->dependencyMocks['handler']->expects(static::once())
            ->method('setBillingAddressDataFromArray')
            ->with($input['shipping_address'], $this->magentoQuote);
        $this->dependencyMocks['handler']->expects(static::once())
            ->method('setShippingAddressDataFromArray')
            ->with($input['shipping_address'], $this->magentoQuote);

        $this->guest->applyKlarnaAddressData($input, $this->magentoQuote);
    }

    public function testApplyKlarnaAddressDataSettingEmail(): void
    {
        $input = [
            'shipping_address' => [
                'a' => 'b',
                'c' => 'd',
                'email' => 'f'
            ]
        ];
        $this->quoteAddress->expects(static::once())
            ->method('setEmail')
            ->with($input['shipping_address']['email']);

        $this->guest->applyKlarnaAddressData($input, $this->magentoQuote);
    }

    protected function setUp(): void
    {
        $this->guest = parent::setUpMocks(Guest::class);

        $this->quoteAddress = $this->mockFactory->create(QuoteAddress::class);
        $this->magentoQuote = $this->mockFactory->create(Quote::class);
        $this->magentoQuote->method('getBillingAddress')
            ->willReturn($this->quoteAddress);
    }
}
