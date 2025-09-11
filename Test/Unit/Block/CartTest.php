<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Block;

use Klarna\AdminSettings\Model\Configurations\Kec;
use Klarna\Base\Test\Unit\Mock\MockFactory;
use Klarna\Kec\Block\Cart;
use Klarna\Kec\Model\KecConfiguration;
use PHPUnit\Framework\TestCase;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\Store;

/**
 * @coversDefaultClass \Klarna\Kec\Block\Cart
 */
class CartTest extends TestCase
{
    /**
     * @var MockFactory
     */
    private MockFactory $mockFactory;
    /**
     * @var Cart
     */
    private Cart $cart;
    /**
     * @var Kec
     */
    private Kec $kec;
    /**
     * @var KecConfiguration
     */
    private KecConfiguration $kecConfiguration;

    public function testIsShowableNotGenerallyEnabledAndNotEnabledOnCart(): void
    {
        $this->kecConfiguration->method('isEnabledAndValidCountry')
            ->willReturn(false);
        $this->kec->method('isEnabledOnCartPage')
            ->willReturn(false);

        static::assertFalse($this->cart->isShowable());
    }

    public function testIsShowableJustGenerallyEnabled(): void
    {
        $this->kecConfiguration->method('isEnabledAndValidCountry')
            ->willReturn(true);
        $this->kec->method('isEnabledOnCartPage')
            ->willReturn(false);

        static::assertFalse($this->cart->isShowable());
    }

    public function testIsShowableJustEnabledOnCart(): void
    {
        $this->kecConfiguration->method('isEnabledAndValidCountry')
            ->willReturn(false);
        $this->kec->method('isEnabledOnCartPage')
            ->willReturn(true);

        static::assertFalse($this->cart->isShowable());
    }

    public function testIsShowableGenerallyAndOnCartEnabled(): void
    {
        $this->kecConfiguration->method('isEnabledAndValidCountry')
            ->willReturn(true);
        $this->kec->method('isEnabledOnCartPage')
            ->willReturn(true);

        static::assertTrue($this->cart->isShowable());
    }

    public function testGetThemeReturningValue(): void
    {
        $result = 'output';
        $this->kecConfiguration->method('getTheme')
            ->willReturn($result);

        static::assertEquals($result, $this->cart->getTheme());
    }

    public function testGetShapeReturningValue(): void
    {
        $result = 'output';
        $this->kecConfiguration->method('getShape')
            ->willReturn($result);

        static::assertEquals($result, $this->cart->getShape());
    }

    public function testGetClientIdReturningValue(): void
    {
        $result = 'output';
        $this->kecConfiguration->method('getClientId')
            ->willReturn($result);

        static::assertEquals($result, $this->cart->getClientId());
    }

    public function testGetAuthCallbackTokenReturnsNotEmptyStringExistingQuote(): void
    {
        $expected = 'existing_value';
        $this->kecConfiguration->method('getAuthCallbackToken')
            ->willReturn($expected);
        static::assertEquals($expected, $this->cart->getAuthCallbackToken(true));
    }

    public function testGetAuthCallbackTokenReturnsNotEmptyStringNewQuote(): void
    {
        $expected = 'new_value';
        $this->kecConfiguration->method('getAuthCallbackToken')
            ->willReturn($expected);
        static::assertEquals($expected, $this->cart->getAuthCallbackToken(false));
    }

    protected function setUp(): void
    {
        $this->mockFactory = new MockFactory($this);

        $store = $this->mockFactory->create(Store::class);
        $storeManager = $this->mockFactory->create(StoreManager::class);
        $context = $this->mockFactory->create(Context::class);
        $this->kec = $this->mockFactory->create(Kec::class);
        $this->kecConfiguration = $this->mockFactory->create(KecConfiguration::class);

        $storeManager->method('getStore')
            ->willReturn($store);
        $context->method('getStoreManager')
            ->willReturn($storeManager);
        $this->kecConfiguration->method('getKecConfig')
            ->willReturn($this->kec);

        $this->cart = new Cart(
            $context,
            $this->kecConfiguration,
            []
        );
    }
}