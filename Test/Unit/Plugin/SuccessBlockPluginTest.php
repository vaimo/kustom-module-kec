<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Test\Unit\Plugin;

use Klarna\Base\Test\Unit\Mock\TestCase;
use Klarna\Kec\Plugin\SuccessBlockPlugin;
use Magento\Checkout\Block\Onepage\Success;

class SuccessBlockPluginTest extends TestCase
{
    /**
     * @var SuccessBlockPlugin
     */
    private SuccessBlockPlugin $successBlockPlugin;
    /**
     * @var Success
     */
    private Success $success;

    public function testAfterGetAdditionalInfoHtmlNoKecSessionImpliesReturningOriginalResult(): void
    {
        $this->dependencyMocks['kecSession']
            ->method('entryExists')
            ->willReturn(false);

        $expected = 'a';
        static::assertEquals($expected, $this->successBlockPlugin->afterGetAdditionalInfoHtml($this->success, $expected));
    }

    public function testAfterGetAdditionalInfoHtmlKecSessionImpliesReturningModifiedResult(): void
    {
        $this->dependencyMocks['kecSession']
            ->method('entryExists')
            ->willReturn(true);
        $this->dependencyMocks['kecSession']
            ->expects($this->once())
            ->method('drop');

        $input = 'a';
        static::assertNotEquals($input, $this->successBlockPlugin->afterGetAdditionalInfoHtml($this->success, $input));
    }

    protected function setUp(): void
    {
        $this->successBlockPlugin = parent::setUpMocks(SuccessBlockPlugin::class);
        $this->success = $this->mockFactory->create(Success::class);
    }
}