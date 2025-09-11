<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Block;

use Exception;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @internal
 */
abstract class PlacementAbstract extends BaseAbstract
{
    /**
     * Getting back the auth callback token
     *
     * @param bool $useExistingQuote
     * @return string
     * @throws Exception
     */
    public function getAuthCallbackToken(bool $useExistingQuote): string
    {
        return $this->kecConfiguration->getAuthCallbackToken($useExistingQuote);
    }

    /**
     * Returns true if its showable
     *
     * @return bool
     */
    abstract public function isShowable(): bool;

    /**
     * Getting back the theme
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getTheme(): string
    {
        return $this->kecConfiguration->getTheme($this->_storeManager->getStore());
    }

    /**
     * Getting back the shape
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getShape(): string
    {
        return $this->kecConfiguration->getShape($this->_storeManager->getStore());
    }

    /**
     * Getting back the locale
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getLocale(): string
    {
        return $this->kecConfiguration->getLocale($this->_storeManager->getStore());
    }

    /**
     * Getting back the client Id
     *
     * @return string
     * @throws NoSuchEntityException|Exception
     */
    public function getClientId(): string
    {
        return $this->kecConfiguration->getClientId($this->_storeManager->getStore());
    }

    /**
     * Returns true if KEC is enabled and the country is allowed
     *
     * @return bool
     * @throws NoSuchEntityException|Exception
     */
    public function isEnabledAndValidCountry(): bool
    {
        return $this->kecConfiguration->isEnabledAndValidCountry($this->_storeManager->getStore());
    }
}
