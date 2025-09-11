<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Controller\Klarna;

use Klarna\Base\Controller\CsrfAbstract;
use Klarna\Base\Model\Responder\Result;
use Klarna\Kec\Model\Update\Handler;
use Klarna\Kp\Model\Payment\Kp;
use Klarna\Logger\Api\LoggerInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

/**
 * @api
 */
class UpdateQuoteAddress extends CsrfAbstract implements HttpPostActionInterface
{
    /**
     * @var Result
     */
    private Result $result;
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;
    /**
     * @var Handler
     */
    private Handler $handler;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * @param Result $result
     * @param RequestInterface $request
     * @param Handler $handler
     * @param LoggerInterface $logger
     * @param UrlInterface $urlBuilder
     * @codeCoverageIgnore
     */
    public function __construct(
        Result $result,
        RequestInterface $request,
        Handler $handler,
        LoggerInterface $logger,
        UrlInterface $urlBuilder
    ) {
        $this->result = $result;
        $this->request = $request;
        $this->handler = $handler;
        $this->logger = $logger;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        try {
            $rawParameter = $this->request->getParams();

            $requiredFields = [
                'addresses',
                'additional_input',
                'session_id',
                'client_token'
            ];
            foreach ($requiredFields as $requiredField) {
                if (!isset($rawParameter[$requiredField])) {
                    return $this->result->getJsonResult(400, ['error_message' => $requiredField . ' key is missing']);
                }
            }

            $rawParameter['additional_input'] = json_decode($rawParameter['additional_input'], true);
            if ($rawParameter['additional_input'] === null) {
                return $this->result->getJsonResult(400, [
                    'error_message' => 'Invalid JSON for key additional_input'
                ]);
            }

            if ($rawParameter['additional_input']['use_existing_quote'] === 0) {
                if (!isset($rawParameter['product'])) {
                    return $this->result->getJsonResult(400, ['error_message' => 'Product ID is missing']);
                }
                if (!isset($rawParameter['qty'])) {
                    return $this->result->getJsonResult(400, ['error_message' => 'Qty is missing']);
                }
            }

            $rawParameter['addresses'] = json_decode($rawParameter['addresses'], true);
            if ($rawParameter['addresses'] === null) {
                return $this->result->getJsonResult(400, [
                    'error_message' => 'Invalid JSON for key addresses'
                ]);
            }

            $this->handler->updateMagentoQuoteByKlarnaAddressData(
                $rawParameter,
                $rawParameter['addresses']
            );

            $httpCode = 200;
            $result = [];
            $result['url'] = $this->urlBuilder->getUrl('checkout', ['_fragment' => 'payment']);
            $result['method'] = Kp::ONE_KLARNA_PAYMENT_METHOD_CODE_WITH_PREFIX;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $result['error_message'] = $e->getMessage();
            $httpCode = 400;
        }

        $result['status'] = $httpCode;
        return $this->result->getJsonResult($httpCode, $result);
    }
}
