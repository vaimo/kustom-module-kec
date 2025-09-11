<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Test\Unit\Model;

use Klarna\Base\Test\Unit\Mock\TestCase;
use Klarna\Kec\Model\Session;
use Magento\Customer\Model\Session as CustomerSession;

class SessionTest extends TestCase
{
    /**
     * @var Session
     */
    private Session $session;

    public function testDropNoEntryExistsImpliesCustomerSessionIsUnchanged(): void
    {
        $this->dependencyMocks['customerSession']->expects($this->never())
            ->method('setCustomData');
        $this->session->drop();
    }

    public function testDropEntryExistsImpliesCustomerSessionWillBeChanged(): void
    {
        $data = [
            'a' => 'b',
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => '1',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => '2'
        ];
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn($data);

        $this->dependencyMocks['customerSession']->expects($this->once())
            ->method('setCustomData')
            ->with(['a' => 'b']);
        $this->session->drop();
    }

    public function testSaveQuoteIdsSavingToEmptyCustomData(): void
    {
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn([]);

        $expected = [
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => '1',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => '2'
        ];
        $this->dependencyMocks['customerSession']->expects($this->once())
            ->method('setCustomData')
            ->with($expected);

        $this->session->saveQuoteIds('2', '1');
    }

    public function testSaveQuoteIdsSavingToNotEmptyCustomData(): void
    {
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn(['a' => 'b']);

        $expected = [
            'a' => 'b',
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => '1',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => '2'
        ];
        $this->dependencyMocks['customerSession']->expects($this->once())
            ->method('setCustomData')
            ->with($expected);

        $this->session->saveQuoteIds('2', '1');
    }

    public function testSaveQuoteIdsOverwritingKlarnaValuesInTheCustomData(): void
    {
        $original = [
            'a' => 'b',
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => '5',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => '7'
        ];
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn($original);

        $expected = [
            'a' => 'b',
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => '1',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => '2'
        ];
        $this->dependencyMocks['customerSession']->expects($this->once())
            ->method('setCustomData')
            ->with($expected);

        $this->session->saveQuoteIds('2', '1');
    }

    public function testSaveQuoteIdsIndicatingNullValueForTheOldId(): void
    {
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn(['a' => 'b']);

        $expected = [
            'a' => 'b',
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => null,
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => '2'
        ];
        $this->dependencyMocks['customerSession']->expects($this->once())
            ->method('setCustomData')
            ->with($expected);

        $this->session->saveQuoteIds('2', null);
    }

    public function testEntryExistsNoCustomDataExistsImpliesReturningFalse(): void
    {
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn(null);

        static::assertFalse($this->session->entryExists());
    }

    public function testEntryExistsCustomDataArrayIsEmptyImpliesReturningFalse(): void
    {
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn([]);

        static::assertFalse($this->session->entryExists());
    }

    public function testEntryExistsCustomDataHasNoKlarnaKeyImpliesReturningFalse(): void
    {
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn(['a' => 'b']);

        static::assertFalse($this->session->entryExists());
    }

    public function testEntryExistsCustomDataHasJustKlarnaKeyForOldIdImpliesReturningFalse(): void
    {
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn([Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => 'b']);

        static::assertFalse($this->session->entryExists());
    }

    public function testEntryExistsCustomDataHasJustKlarnaKeyForNewIdImpliesReturningFalse(): void
    {
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn([Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => 'b']);

        static::assertFalse($this->session->entryExists());
    }

    public function testEntryExistsCustomDataHasJustBothKlarnaKeysImpliesReturningTrue(): void
    {
        $output = [
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => 'a',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => 'b'
        ];
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn($output);

        static::assertTrue($this->session->entryExists());
    }

    public function testEntryExistsCustomDataHasBothKlarnaKeysAndAnotherKeyImpliesReturningTrue(): void
    {
        $output = [
            'a' => 'b',
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => 'c',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => 'd'
        ];
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn($output);

        static::assertTrue($this->session->entryExists());
    }

    public function testGetNewQuoteIdNoKlarnaKeyExistsImpliesReturningNull(): void
    {
        static::assertNull($this->session->getNewQuoteId());
    }

    public function testGetNewQuoteIdKlarnaKeysExistsImpliesReturningValue(): void
    {
        $output = [
            'a' => 'b',
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => 'c',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => 'd'
        ];
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn($output);
        static::assertEquals('d', $this->session->getNewQuoteId());
    }

    public function testGetOldQuoteIdNoKlarnaKeyExistsImpliesReturningNull(): void
    {
        static::assertNull($this->session->getOldQuoteId());
    }

    public function testGetOldQuoteIdKlarnaKeysExistsImpliesReturningValue(): void
    {
        $output = [
            'a' => 'b',
            Session::SESSION_KEY_OLD_MAGENTO_QUOTE_ID => 'c',
            Session::SESSION_KEY_NEW_MAGENTO_QUOTE_ID => 'd'
        ];
        $this->dependencyMocks['customerSession']->method('getCustomData')
            ->willReturn($output);
        static::assertEquals('c', $this->session->getOldQuoteId());
    }

    protected function setUp(): void
    {
        $customerSession = $this->createSingleMock(CustomerSession::class, [], ['getCustomData', 'setCustomData']);
        $this->session = parent::setUpMocks(Session::class, [], [CustomerSession::class => $customerSession]);
    }
}
