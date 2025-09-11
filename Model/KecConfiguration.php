<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Model;

use Exception;
use Klarna\AdminSettings\Model\Configurations\Api as ApiConfig;
use Klarna\AdminSettings\Model\Configurations\General;
use Klarna\AdminSettings\Model\Configurations\Kec as KecConfig;
use Klarna\Base\Model\Api\MagentoToKlarnaLocaleMapper;
use Klarna\Kp\Model\QuoteRepository;
use Klarna\Logger\Model\Logger;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Math\Random;
use Magento\Store\Api\Data\StoreInterface;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @internal
 */
class KecConfiguration
{
    /**
     * @var KecConfig
     */
    private KecConfig $kecConfig;

    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var General
     */
    private General $generalConfig;

    /**
     * @var Random
     */
    private Random $randomGenerator;

    /**
     * @var ApiConfig
     */
    private ApiConfig $apiConfig;

    /**
     * @var MagentoToKlarnaLocaleMapper
     */
    private MagentoToKlarnaLocaleMapper $magentoToKlarnaLocaleMapper;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var QuoteRepository
     */
    private QuoteRepository $quoteRepository;

    /**
     * @param KecConfig $kecConfig
     * @param Session $session
     * @param General $generalConfig
     * @param Random $randomGenerator
     * @param ApiConfig $apiConfig
     * @param MagentoToKlarnaLocaleMapper $magentoToKlarnaLocaleMapper
     * @param Logger $logger
     * @param QuoteRepository $quoteRepository
     * @codeCoverageIgnore
     */
    public function __construct(
        KecConfig $kecConfig,
        Session $session,
        General $generalConfig,
        Random $randomGenerator,
        ApiConfig $apiConfig,
        MagentoToKlarnaLocaleMapper $magentoToKlarnaLocaleMapper,
        Logger $logger,
        QuoteRepository $quoteRepository,
    ) {
        $this->kecConfig = $kecConfig;
        $this->session = $session;
        $this->generalConfig = $generalConfig;
        $this->randomGenerator = $randomGenerator;
        $this->apiConfig = $apiConfig;
        $this->magentoToKlarnaLocaleMapper = $magentoToKlarnaLocaleMapper;
        $this->logger = $logger;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Get back the auth callback token from Klarna Payments Quote table or generate a new one if the quote is not set
     *
     * @param bool $useExistingQuote
     * @return string
     * @throws Exception
     */
    public function getAuthCallbackToken(bool $useExistingQuote): string
    {
        try {
            if (!$useExistingQuote) {
                return $this->randomGenerator->getUniqueHash();
            }
            // Even though PHPDoc says it returns int, it returns a string that we need to cast to int
            $quoteId = (int) $this->session->getQuoteId();
            $authCallbackToken = $this->quoteRepository->getByQuoteId($quoteId)
                ->getAuthTokenCallbackToken();

            return $authCallbackToken ?: $this->randomGenerator->getUniqueHash();
        } catch (NoSuchEntityException) {
            return $this->randomGenerator->getUniqueHash();
        } catch (LocalizedException $e) {
            $this->logError($e);
            return '';
        }
    }

    /**
     * Checks and returns if the button can be shown on mini cart
     *
     * @param StoreInterface $store
     * @return bool
     * @throws Exception
     */
    public function showMiniCartKecButton(StoreInterface $store): bool
    {
        return $this->kecConfig->isEnabledOnMiniCart($store) &&
            $this->isEnabledAndValidCountry($store);
    }

    /**
     * Getting back the client Id
     *
     * @param StoreInterface $store
     * @return string
     * @throws Exception
     */
    public function getClientId(StoreInterface $store): string
    {
        try {
            return $this->apiConfig->getClientIdentifier(
                $store,
                $store->getCurrentCurrency()->getCode()
            );
        } catch (LocalizedException $e) {
            $this->logError($e);
            return '';
        }
    }

    /**
     * Getting back the theme
     *
     * @param StoreInterface $store
     * @return string
     */
    public function getTheme(StoreInterface $store): string
    {
        return $this->kecConfig->getTheme($store);
    }

    /**
     * Getting back the shape
     *
     * @param StoreInterface $store
     * @return string
     */
    public function getShape(StoreInterface $store): string
    {
        return $this->kecConfig->getShape($store);
    }

    /**
     * Getting back the locale
     *
     * @param StoreInterface $store
     * @return string
     */
    public function getLocale(StoreInterface $store): string
    {
        return $this->magentoToKlarnaLocaleMapper->getLocale($store);
    }

    /**
     * Returns true if KEC is enabled and the country is allowed
     *
     * @param StoreInterface $store
     * @return bool
     * @throws Exception
     */
    public function isEnabledAndValidCountry(StoreInterface $store): bool
    {
        try {
            $result = $this->kecConfig->isEnabled($store);
            if (!$result) {
                return false;
            }
            $quote = $this->session->getQuote();
            $address = $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
            $quoteCountry = $address->getCountryId();
            if (!$quoteCountry) {
                return true;
            }
            return $this->generalConfig->isCountryAllowed($store, $quoteCountry);
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->logError($e);
            return false;
        }
    }

    /**
     * Returns the Kec configuration
     *
     * @return KecConfig
     */
    public function getKecConfig(): KecConfig
    {
        return $this->kecConfig;
    }

    /**
     * Log the error to Klarna Logger
     *
     * @param Exception $e
     * @return void
     * @throws Exception
     */
    private function logError(Exception $e): void
    {
        $this->logger->log('error', $e);
    }
}
