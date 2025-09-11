<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Test\Unit\Model;

use Klarna\Kec\Model\StopWorkflow;
use Magento\Quote\Model\Quote;
use Klarna\Base\Test\Unit\Mock\TestCase;
use \Klarna\Kp\Model\Quote as KlarnaQuote;

/**
 * @coversDefaultClass \Klarna\Kec\Model\StopWorkflow
 */
class StopWorkflowTest extends TestCase
{
    /**
     * @var Stopworkflow
     */
    private $stopWorkflow;
    /**
     * @var MockObject|Quote
     */
    private $quote;

    private $klarnaQuote;

    public function testStopWorkflowNoEntryExists(): void
    {
        $this->dependencyMocks['kecSession']
            ->expects($this->once())
            ->method('entryExists')
            ->willReturn(false);

        $this->stopWorkflow->stopKecWorkflow();
    }

    public function testStopWorkflowGetQuoteThrowsException(): void
    {
        $this->dependencyMocks['kecSession']
            ->method('entryExists')
            ->willThrowException(new \Exception());

        $this->dependencyMocks['logger']
            ->method('info')
            ->with('No Klarna quote could be found although there was an active KEC session');

        $this->expectException(\Exception::class);
        $this->stopWorkflow->stopKecWorkflow();
    }


    public function testStopWorkflowDropsKecSession(): void
    {
        $this->dependencyMocks['kecSession']
            ->method('entryExists')
            ->willReturn(true);

        $this->dependencyMocks['checkoutSession']
            ->method('getQuote')
            ->willReturn($this->quote);

        $this->dependencyMocks['klarnaQuoteRepository']->method('getActiveByQuote')
            ->willReturn($this->klarnaQuote);

        $this->dependencyMocks['klarnaQuoteRepository']
            ->expects($this->once())
            ->method('save');

        $this->dependencyMocks['kecSession']
            ->expects($this->once())
            ->method('drop');

        $this->stopWorkflow->stopKecWorkflow();
    }
    
    protected function setUp(): void
    {
        $this->stopWorkflow = parent::setUpMocks(StopWorkflow::class);
        $this->quote = $this->mockFactory->create(Quote::class);
        $this->klarnaQuote = $this->mockFactory->create(KlarnaQuote::class);
    }
}
