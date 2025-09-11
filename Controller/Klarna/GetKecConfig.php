<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Controller\Klarna;

use Exception;
use Klarna\Base\Controller\CsrfAbstract;
use Klarna\Kec\Model\KecConfiguration;
use Klarna\Logger\Model\Logger;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Store\Model\StoreManager;

/**
 * @api
 */
class GetKecConfig extends CsrfAbstract implements HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;

    /**
     * @var KecConfiguration
     */
    private KecConfiguration $kecConfiguration;

    /**
     * @var StoreManager
     */
    private StoreManager $storeManager;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param KecConfiguration $kecConfiguration
     * @param JsonFactory $jsonFactory
     * @param StoreManager $storeManager
     * @param RequestInterface $request
     * @param Logger $logger
     * @codeCoverageIgnore
     */
    public function __construct(
        KecConfiguration $kecConfiguration,
        JsonFactory $jsonFactory,
        StoreManager $storeManager,
        RequestInterface $request,
        Logger $logger
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->storeManager = $storeManager;
        $this->kecConfiguration = $kecConfiguration;
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * Returns KEC configuration needed for the mini cart button
     *
     * @return Json
     */
    public function execute(): Json
    {
        $result = $this->jsonFactory->create();
        $kecConfig = [
            'authCallbackToken' => '',
            'isShowable' => false,
            'clientId' => '',
            'theme' => '',
            'shape' => '',
            'locale' => ''
        ];
        try {
            $store = $this->storeManager->getStore();
            if (!$this->kecConfiguration->showMiniCartKecButton($store)) {
                return $result->setData($kecConfig);
            }

            $authCallbackToken = $this->kecConfiguration->getAuthCallbackToken(
                (bool) $this->request->getParam('use_existing_quote', false)
            );
            $clientId = $this->kecConfiguration->getClientId($store);

            $kecConfig['authCallbackToken'] = $authCallbackToken;
            $kecConfig['isShowable'] = true;
            $kecConfig['clientId'] = $clientId;
            $kecConfig['theme'] = $this->kecConfiguration->getTheme($store);
            $kecConfig['shape'] = $this->kecConfiguration->getShape($store);
            $kecConfig['locale'] = $this->kecConfiguration->getLocale($store);
        } catch (Exception $e) {
            $this->logger->log('error', $e);
        }
        return $result->setData($kecConfig);
    }
}
