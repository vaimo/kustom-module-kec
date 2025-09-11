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
use Klarna\Kec\Plugin\SuccessControllerPlugin;
use Magento\Checkout\Controller\Onepage\Success;
use Magento\Framework\View\Result\Page;

class SuccessControllerPluginTest extends TestCase
{
    /**
     * @var SuccessControllerPlugin
     */
    private SuccessControllerPlugin $successControllerPlugin;
    /**
     * @var Success
     */
    private Success $success;

    /**
     * @var Page
     */
    private Page $page;

    public function testAfterExecuteNoKecSessionImpliesCheckoutSessionIsNotAssignedToAnotherQuoteId(): void
    {
        $this->dependencyMocks['kecSession']
            ->method('entryExists')
            ->willReturn(false);
        $this->dependencyMocks['checkoutSession']->expects($this->never())
            ->method('setQuoteId');

        $this->successControllerPlugin->afterExecute($this->success, $this->page);
    }

    public function testAfterExecuteKecSessionImpliesCheckoutSessionIsAssignedToAnotherQuoteId(): void
    {
        $this->dependencyMocks['kecSession']
            ->method('entryExists')
            ->willReturn(true);
        $this->dependencyMocks['kecSession']
            ->method('getOldQuoteId')
            ->willReturn('1');
        $this->dependencyMocks['checkoutSession']->expects($this->once())
            ->method('setQuoteId')
            ->with('1');

        $this->successControllerPlugin->afterExecute($this->success, $this->page);
    }

    protected function setUp(): void
    {
        $this->successControllerPlugin = parent::setUpMocks(SuccessControllerPlugin::class);

        $this->success = $this->mockFactory->create(Success::class);
        $this->page = $this->mockFactory->create(Page::class);
    }
}