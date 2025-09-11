<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Model;

use Klarna\Kec\Model\Session as KecSession;
use Klarna\Kp\Api\QuoteRepositoryInterface;
use Klarna\Logger\Api\LoggerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @internal
 */
class StopWorkflow
{
    /**
     * @var KecSession
     */
    private KecSession $kecSession;
    /**
     * @var CheckoutSession
     */
    private CheckoutSession $checkoutSession;
    /**
     * @var QuoteRepositoryInterface
     */
    private QuoteRepositoryInterface $klarnaQuoteRepository;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param KecSession $kecSession
     * @param CheckoutSession $checkoutSession
     * @param QuoteRepositoryInterface $klarnaQuoteRepository
     * @param LoggerInterface $logger
     * @codeCoverageIgnore
     */
    public function __construct(
        KecSession $kecSession,
        CheckoutSession $checkoutSession,
        QuoteRepositoryInterface $klarnaQuoteRepository,
        LoggerInterface $logger
    ) {
        $this->kecSession = $kecSession;
        $this->checkoutSession = $checkoutSession;
        $this->klarnaQuoteRepository = $klarnaQuoteRepository;
        $this->logger = $logger;
    }

    /**
     * Gets the Klarna quote and sets it to inactive, then drops the KEC session
     *
     * @return void
     */
    public function stopKecWorkflow(): void
    {
        if (!$this->kecSession->entryExists()) {
            return;
        }

        try {
            $magentoQuote = $this->checkoutSession->getQuote();
            $klarnaQuote = $this->klarnaQuoteRepository->getActiveByQuote($magentoQuote);
            $klarnaQuote->setIsActive(false);
            $this->klarnaQuoteRepository->save($klarnaQuote);
        } catch (\Exception $e) {
            $this->logger->info('No Klarna quote could be found although there was an active KEC session');
        }

        $this->kecSession->drop();
    }
}
