<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Test\Unit\Model;

use Klarna\Kec\Model\KecConfiguration;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Framework\Currency;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Magento\Store\Api\Data\StoreInterface;
use Klarna\Kp\Model\Quote as KlarnaQuote;

/**
 * @coversDefaultClass \Klarna\Kec\Model\KecConfiguration
 */
class KecConfigurationTest extends TestCase
{
    /**
     * @var KecConfiguration
     */
    private $kecConfiguration;

    /**
     * @var StoreInterface
     */
    private $store;

    /**
     * @var Quote
     */
    private $quote;

    /**
     * @var KlarnaQuote
     */
    private $klarnaQuote;

    /**
     * @var Currency
     */
    private $currency;

    public function testGetAuthCallbackTokenReturnsAuthTokenFromKlarnaPaymentsRepository()
    {
        $this->dependencyMocks['randomGenerator']->expects($this->never())
            ->method('getUniqueHash');
        $this->dependencyMocks['session']->expects($this->once())
            ->method('getQuoteId')
            ->willReturn('1');
        $this->dependencyMocks['quoteRepository']->expects($this->once())
            ->method('getByQuoteId')
            ->willReturn($this->klarnaQuote);
        $this->klarnaQuote->expects($this->once())
            ->method('getAuthTokenCallbackToken')
            ->willReturn('auth_token');

        static::assertEquals('auth_token', $this->kecConfiguration->getAuthCallbackToken(true));
    }

    public function testGetAuthCallbackTokenReturnsAuthTokenIfNotUsingExistingQuote()
    {
        $this->dependencyMocks['randomGenerator']->expects($this->once())
            ->method('getUniqueHash')
            ->willReturn('generated_auth_token');

        static::assertEquals('generated_auth_token', $this->kecConfiguration->getAuthCallbackToken(false));
    }

    public function testShowMiniCartKecButtonReturnsTrue()
    {
        $this->mockIsEnabledAndValidCountry(true);
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('isEnabledOnMiniCart')
            ->with($this->store)
            ->willReturn(true);
        static::assertTrue($this->kecConfiguration->showMiniCartKecButton($this->store));
    }

    public function testShowMiniCartKecButtonIsEnabledOnMiniCartIsReturnsFalse()
    {
        $this->mockIsEnabledAndValidCountry(false);
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('isEnabledOnMiniCart')
            ->with($this->store)
            ->willReturn(false);

        static::assertFalse($this->kecConfiguration->showMiniCartKecButton($this->store));
    }

    public function testShowMiniCartKecButtonIsEnabledAndValidCountryReturnsFalse()
    {
        $this->mockIsEnabledAndValidCountry(false);
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('isEnabledOnMiniCart')
            ->with($this->store)
            ->willReturn(true);

        static::assertFalse($this->kecConfiguration->showMiniCartKecButton($this->store));
    }

    public function testClientIdReturnsClientId()
    {
        $this->dependencyMocks['apiConfig']->expects($this->once())
            ->method('getClientIdentifier')
            ->willReturn('client_id');
        $this->store->expects($this->once())
            ->method('getCurrentCurrency')
            ->willReturn($this->currency);
        $this->currency->expects($this->once())
            ->method('getCode')
            ->willReturn('EUR');

        static::assertEquals('client_id', $this->kecConfiguration->getClientId($this->store));
    }

    public function testClientIdReturnsEmptyString()
    {
        $this->dependencyMocks['apiConfig']->expects($this->once())
            ->method('getClientIdentifier')
            ->willReturn('');
        $this->store->expects($this->once())
            ->method('getCurrentCurrency')
            ->willReturn($this->currency);
        $this->currency->expects($this->once())
            ->method('getCode')
            ->willReturn('AUD');

        static::assertEquals('', $this->kecConfiguration->getClientId($this->store));
    }

    public function testClientIdReturnsEmptyStringDueToException()
    {
        $this->store->expects($this->once())
            ->method('getCurrentCurrency')
            ->willThrowException(new LocalizedException(__('Could not get current currency')));

        static::assertEquals('', $this->kecConfiguration->getClientId($this->store));
    }

    public function testGetThemeReturnsDarkTheme()
    {
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('getTheme')
            ->with($this->store)
            ->willReturn('dark');

        static::assertEquals('dark', $this->kecConfiguration->getTheme($this->store));
    }

    public function testGetThemeReturnsEmptyString()
    {
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('getTheme')
            ->with($this->store)
            ->willReturn('');

        static::assertEquals('', $this->kecConfiguration->getTheme($this->store));
    }

    public function testGetShapeReturnsRoundedShape()
    {
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('getShape')
            ->with($this->store)
            ->willReturn('rounded');

        static::assertEquals('rounded', $this->kecConfiguration->getShape($this->store));
    }

    public function testGetShapeReturnsEmptyString()
    {
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('getShape')
            ->with($this->store)
            ->willReturn('');

        static::assertEquals('', $this->kecConfiguration->getShape($this->store));
    }

    public function testGetLocaleReturnsDELocale()
    {
        $this->dependencyMocks['magentoToKlarnaLocaleMapper']->expects($this->once())
            ->method('getLocale')
            ->willReturn('de-DE');

        static::assertEquals('de-DE', $this->kecConfiguration->getLocale($this->store));
    }

    private function mockIsEnabledAndValidCountry(bool $isCountryAllowedReturn): void
    {
        $deCountryId = 'de';
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('isEnabledOnMiniCart')
            ->with($this->store)
            ->willReturn(true);
        $this->dependencyMocks['kecConfig']->expects($this->once())
            ->method('isEnabled')
            ->with($this->store)
            ->willReturn(true);
        $this->dependencyMocks['session']->expects($this->once())
            ->method('getQuote')
            ->willReturn($this->quote);
        $this->quote->expects($this->once())
            ->method('isVirtual')
            ->willReturn(false);
        $address = $this->createSingleMock(Currency::class, [], [
            'getCountryId',
        ]);
        $this->quote->expects($this->once())
            ->method('getShippingAddress')
            ->willReturn($address);
        $address->expects($this->once())
            ->method('getCountryId')
            ->willReturn($deCountryId);
        $this->dependencyMocks['generalConfig']->expects($this->once())
            ->method('isCountryAllowed')
            ->with($this->store, $deCountryId)
            ->willReturn($isCountryAllowedReturn);
    }

    protected function setup(): void
    {
        $this->kecConfiguration = parent::setUpMocks(KecConfiguration::class);
        $this->quote = $this->createSingleMock(Quote::class,
            [
                'isVirtual',
                'getBillingAddress',
                'getShippingAddress',
            ],
            [
                'getCountryId'
            ]
        );
        $this->currency = $this->createSingleMock(Currency::class,
            [],
            [
                'getCode',
            ]
        );
        $this->store = $this->createSingleMock(StoreInterface::class,
            [
                'getId',
                'setId',
                'getCode',
                'setCode',
                'getName',
                'setName',
                'getWebsiteId',
                'setWebsiteId',
                'getStoreGroupId',
                'setStoreGroupId',
                'getIsActive',
                'setIsActive',
                'getExtensionAttributes',
                'setExtensionAttributes',
            ],
            [
                'getCurrentCurrency',
                'getLocale',
                'create'
            ]
        );
        $this->klarnaQuote = $this->createSingleMock(KlarnaQuote::class);
    }
}
