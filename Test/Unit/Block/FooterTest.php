<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Block;

use Klarna\AdminSettings\Model\Configurations\Kec;
use Klarna\Kec\Model\KecConfiguration;
use PHPUnit\Framework\TestCase;
use Magento\Store\Model\Store;
use Klarna\Kec\Block\Footer;
use Klarna\Base\Test\Unit\Mock\MockFactory;
use Magento\Store\Model\StoreManager;
use Magento\Framework\View\Element\Template\Context;

/**
 * @coversDefaultClass \Klarna\Kec\Block\Footer
 */
class FooterTest extends TestCase
{
    /**
     * @var MockFactory
     */
    private MockFactory $mockFactory;
    /**
     * @var Footer
     */
    private Footer $footer;
    /**
     * @var Kec
     */
    private Kec $kec;
    /**
     * @var KecConfiguration
     */
    private KecConfiguration $kecConfiguration;

    public function testIsKecEnabledReturningFalse(): void
    {
        $this->kec->method('isEnabled')
            ->willReturn(false);

        static::assertFalse($this->footer->isKecEnabled());
    }

    public function testIsKecEnabledReturningTrue(): void
    {
        $this->kec->method('isEnabled')
            ->willReturn(true);

        static::assertTrue($this->footer->isKecEnabled());
    }

    public function testGetJsUrlReturningValue(): void
    {
        static::assertEquals('https://x.klarnacdn.net/kp/lib/v1/api.js', $this->footer->getJsUrl());
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

        $this->footer = new Footer(
            $context,
            $this->kecConfiguration,
            []
        );
    }
}