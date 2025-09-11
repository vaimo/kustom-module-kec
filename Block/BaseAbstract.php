<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Block;

use Klarna\AdminSettings\Model\Configurations\Kec as KecConfig;
use Klarna\Kec\Model\KecConfiguration;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * @internal
 */
abstract class BaseAbstract extends Template
{
    /**
     * @var KecConfiguration
     */
    protected KecConfiguration $kecConfiguration;

    /**
     * @param Context $context
     * @param KecConfiguration $kecConfiguration
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        KecConfiguration $kecConfiguration,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->kecConfiguration = $kecConfiguration;
    }

    /**
     * Returns the Kec configuration
     *
     * @return KecConfig
     */
    public function getKecConfig(): KecConfig
    {
        return $this->kecConfiguration->getKecConfig();
    }

    /**
     * Returns true if the button can be shown on the cart page
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isKecEnabled(): bool
    {
        return $this->getKecConfig()
            ->isEnabled($this->_storeManager->getStore());
    }
}
