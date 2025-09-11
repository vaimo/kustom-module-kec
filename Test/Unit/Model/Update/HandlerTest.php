<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update;

use Klarna\Kec\Model\Update\Handler;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\Handler
 */
class HandlerTest extends TestCase
{
    /**
     * @var Quote
     */
    private Quote $magentoQuote;
    /**
     * @var Handler
     */
    private Handler $handler;

    public function testUpdateMagentoQuoteByKlarnaAddressDataCallingDisablementOfExistingQuoteLogic(): void
    {
        $this->dependencyMocks['klarnaQuoteUpdate']->expects(static::once())
            ->method('disableByMagentoQuote')
            ->with($this->magentoQuote);

        static::assertSame($this->magentoQuote,
            $this->handler->updateMagentoQuoteByKlarnaAddressData([], [])
        );
    }

    public function testUpdateMagentoQuoteByKlarnaAddressDataUpdatingQuoteAndSavingIt(): void
    {
        $this->dependencyMocks['magentoQuoteRepository']->expects(static::once())
            ->method('save')
            ->with($this->magentoQuote);

        static::assertSame($this->magentoQuote,
            $this->handler->updateMagentoQuoteByKlarnaAddressData([], [])
        );
    }

    protected function setUp(): void
    {
        $this->handler = parent::setUpMocks(Handler::class);

        $this->magentoQuote = $this->mockFactory->create(Quote::class, ['getId']);
        $this->magentoQuote->method('getId')
            ->willReturn('1');
        $this->dependencyMocks['quoteFetcher']->method('getMagentoQuote')
            ->willReturn($this->magentoQuote);
    }
}