<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update;

use Klarna\Kec\Model\Update\KlarnaQuote;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Framework\Exception\NoSuchEntityException;
use Klarna\Kp\Model\Quote as KlarnaQuoteModel;
use Magento\Quote\Model\Quote;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\KlarnaQuote
 */
class KlarnaQuoteTest extends TestCase
{
    /**
     * @var KlarnaQuote
     */
    private KlarnaQuote $klarnaQuoteTestClass;
    /**
     * @var KlarnaQuoteModel
     */
    private KlarnaQuoteModel $klarnaQuote;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;

    public function testDisableByMagentoQuoteIdNoKlarnaQuoteExists(): void
    {
        $this->dependencyMocks['klarnaQuoteRepository']->method('getActiveByQuote')
            ->willThrowException(new NoSuchEntityException());
        $this->dependencyMocks['klarnaQuoteRepository']->expects(static::never())
            ->method('save');

        $this->klarnaQuoteTestClass->disableByMagentoQuote($this->magentoQuote);
    }

    public function testDisableByMagentoQuoteIdKlarnaQuoteExists(): void
    {
        $this->dependencyMocks['klarnaQuoteRepository']->method('getActiveByQuote')
            ->willReturn($this->klarnaQuote);
        $this->klarnaQuote->expects(static::once())
            ->method('setIsActive')
            ->with(0);
        $this->dependencyMocks['klarnaQuoteRepository']->expects(static::once())
            ->method('save');

        $this->klarnaQuoteTestClass->disableByMagentoQuote($this->magentoQuote);
    }

    public function testCreateInstanceCreatedAndSaved(): void
    {
        $this->dependencyMocks['klarnaQuoteFactory']->expects(static::once())
            ->method('create')
            ->willReturn($this->klarnaQuote);
        $this->dependencyMocks['klarnaQuoteRepository']->expects(static::once())
            ->method('save')
            ->with($this->klarnaQuote);

        $parameter = [
            'client_token' => '1',
            'session_id' => '2',
            'additional_input' => [
                'auth_callback_token' => '3'
            ]
        ];
        $this->klarnaQuoteTestClass->create('1', $parameter);
    }

    protected function setUp(): void
    {
        $this->klarnaQuoteTestClass = parent::setUpMocks(KlarnaQuote::class);

        $this->klarnaQuote = $this->mockFactory->create(KlarnaQuoteModel::class);
        $this->magentoQuote = $this->mockFactory->create(Quote::class);
        $this->magentoQuote->method('getId')
            ->willReturn('1');
    }
}
