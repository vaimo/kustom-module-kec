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
use Klarna\Kec\Block\Product;
use Klarna\Kec\Model\KecConfiguration;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Klarna\Kec\Block\Product
 */
class ProductTest extends TestCase
{
    /**
     * @var MockFactory
     */
    private MockFactory $mockFactory;
    /**
     * @var Product
     */
    private Product $product;
    /**
     * @var Kec
     */
    private Kec $kec;
    /**
     * @var KecConfiguration
     */
    private KecConfiguration $kecConfiguration;

    public function testIsShowableNotGenerallyEnabledAndNotEnabledOnProductPage(): void
    {
        $this->kecConfiguration->method('isEnabledAndValidCountry')
            ->willReturn(false);
        $this->kec->method('isEnabledOnProductPage')
            ->willReturn(false);

        static::assertFalse($this->product->isShowable());
    }

    public function testIsShowableGenerallyEnabledAndNotEnabledOnProductPage(): void
    {
        $this->kecConfiguration->method('isEnabledAndValidCountry')
            ->willReturn(true);
        $this->kec->method('isEnabledOnProductPage')
            ->willReturn(false);

        static::assertFalse($this->product->isShowable());
    }

    public function testIsShowableNotGenerallyEnabledAndEnabledOnProductPage(): void
    {
        $this->kecConfiguration->method('isEnabledAndValidCountry')
            ->willReturn(false);
        $this->kec->method('isEnabledOnProductPage')
            ->willReturn(true);

        static::assertFalse($this->product->isShowable());
    }

    public function testIsShowableGenerallyEnabledAndEnabledonProductPage(): void
    {
        $this->kecConfiguration->method('isEnabledAndValidCountry')
            ->willReturn(true);
        $this->kec->method('isEnabledOnProductPage')
            ->willReturn(true);

        static::assertTrue($this->product->isShowable());
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

        $this->product = new Product(
            $context,
            $this->kecConfiguration,
            []
        );
    }
}