<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Test\Integration\Controller\Klarna;

use Klarna\Base\Test\Integration\Helper\ControllerTestCase;
use Magento\Quote\Api\Data\AddressInterface;

/**
 * @internal
 */
class GetPayloadTest extends ControllerTestCase
{
    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_us_postal_36104.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture default/payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteUsingCurrentQuoteOfGuestCustomerOnDefaultLevelForShopSetup1(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('USD');

        $product = $this->productRepository->get('simple');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getUsAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'US',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');
        $this->validator->performAllGeneralUsChecks($response['body'], $quote);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_us_postal_36104.php
     *
     * @magentoConfigFixture current_store general/country/default US
     * @magentoConfigFixture current_store general/store_information/country_id US
     * @magentoConfigFixture current_store general/store_information/region_id 82
     * @magentoConfigFixture current_store tax/defaults/country US
     * @magentoConfigFixture current_store tax/calculation/price_includes_tax 0
     * @magentoConfigFixture current_store tax/calculation/shipping_includes_tax 0
     * @magentoConfigFixture current_store tax/calculation/discount_tax 0
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store shipping/origin/country_id US
     * @magentoConfigFixture current_store shipping/origin/region_id 1
     * @magentoConfigFixture current_store tax/display/shipping 1
     * @magentoConfigFixture current_store tax/display/type 1
     * @magentoConfigFixture default/currency/options/base USD
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     *
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteUsingCurrentQuoteOfGuestCustomerOnStoreLevelForShopSetup1(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('USD');

        $product = $this->productRepository->get('simple');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getUsAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'US',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');
        $this->validator->performAllGeneralUsChecks($response['body'], $quote);
    }

    public function testExecuteUsingCurrentQuoteLoggedInCustomerHasNoAddressInAccountForShopSetup1(): void
    {
        self::markTestSkipped('Integration testing framework has to be extended for customers to make it work');
    }

    public function testExecuteUsingNewQuoteLoggedInCustomerHasNoAddressInAccountForShopSetup1(): void
    {
        self::markTestSkipped('Integration testing framework has to be extended for customers to make it work');
    }

    public function testExecuteUsingCurrentQuoteLoggedInCustomerHasAddressInAccountForShopSetup1(): void
    {
        self::markTestSkipped('Integration testing framework has to be extended for customers to make it work');
    }

    public function testExecuteUsingNewQuoteLoggedInCustomerHasAddressInAccountForShopSetup1(): void
    {
        self::markTestSkipped('Integration testing framework has to be extended for customers to make it work');
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_virtual.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_us_postal_36104.php
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteUsingCurrentVirtualQuoteOfGuestCustomerOnDefaultLevelForShopSetup1(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('USD');

        $product = $this->productRepository->get('virtual-product');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getUsAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'US',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');
        $orderLines = $response['body']['order_lines'];

        $this->validator->isKlarnaSumTotalsShopGrandTotalSame($orderLines, $quote);
        $this->validator->isKlarnaShopUsTaxSame($orderLines, $quote);
        $this->validator->isKlarnaUsSumProductTotalsShopSubtotalSame($orderLines, $quote);
        $this->validator->isKlarnaUsTaxShippingTotalEqualUnitQty($orderLines);
        $this->validator->isKlarnaProductTotalEqualUnitQty($orderLines);
        $this->validator->isKlarnaShippingOrderlineItemMissing($orderLines);
        $this->validator->isKlarnaSumTotalsKlarnaOrderAmountSame($response['body']);
        $this->validator->isKlarnaSumTaxTotalsKlarnaOrderTaxAmountSame($response['body']);
        $this->validator->isKlarnaOrderAmountShopOrderAmountSame($response['body'], $quote);
        $this->validator->isKlarnaOrderTaxAmountShopTaxSame($response['body'], $quote);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_virtual.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_us_postal_36104.php
     *
     * @magentoConfigFixture current_store general/country/default US
     * @magentoConfigFixture current_store general/store_information/country_id US
     * @magentoConfigFixture current_store general/store_information/region_id 82
     * @magentoConfigFixture current_store tax/defaults/country US
     * @magentoConfigFixture current_store tax/calculation/price_includes_tax 0
     * @magentoConfigFixture current_store tax/calculation/shipping_includes_tax 0
     * @magentoConfigFixture current_store tax/calculation/discount_tax 0
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store shipping/origin/country_id US
     * @magentoConfigFixture current_store shipping/origin/region_id 1
     * @magentoConfigFixture current_store tax/display/shipping 1
     * @magentoConfigFixture current_store tax/display/type 1
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 1
     * @magentoConfigFixture default/currency/options/base USD
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteUsingCurrentVirtualQuoteOfGuestCustomerOnStoreLevelForShopSetup1(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('USD');

        $product = $this->productRepository->get('virtual-product');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getUsAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'US',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');
        $orderLines = $response['body']['order_lines'];

        $this->validator->isKlarnaSumTotalsShopGrandTotalSame($orderLines, $quote);
        $this->validator->isKlarnaShopUsTaxSame($orderLines, $quote);
        $this->validator->isKlarnaUsSumProductTotalsShopSubtotalSame($orderLines, $quote);
        $this->validator->isKlarnaUsTaxShippingTotalEqualUnitQty($orderLines);
        $this->validator->isKlarnaProductTotalEqualUnitQty($orderLines);
        $this->validator->isKlarnaShippingOrderlineItemMissing($orderLines);
        $this->validator->isKlarnaSumTotalsKlarnaOrderAmountSame($response['body']);
        $this->validator->isKlarnaSumTaxTotalsKlarnaOrderTaxAmountSame($response['body']);
        $this->validator->isKlarnaOrderAmountShopOrderAmountSame($response['body'], $quote);
        $this->validator->isKlarnaOrderTaxAmountShopTaxSame($response['body'], $quote);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_de_postal_13055.php
     *
     * @magentoConfigFixture default/general/country/default DE
     * @magentoConfigFixture default/general/store_information/country_id DE
     * @magentoConfigFixture default/tax/defaults/country DE
     * @magentoConfigFixture default/tax/calculation/price_includes_tax 1
     * @magentoConfigFixture default/tax/calculation/shipping_includes_tax 1
     * @magentoConfigFixture default/tax/calculation/discount_tax 1
     * @magentoConfigFixture default/tax/display/shipping 2
     * @magentoConfigFixture default/tax/display/type 2
     * @magentoConfigFixture default/shipping/origin/country_id DE
     * @magentoConfigFixture default/shipping/origin/region_id 82
     * @magentoConfigFixture default/tax/display/shipping 2
     * @magentoConfigFixture default/tax/display/type 2
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteUsingCurrentQuoteOfGuestCustomerOnDefaultLevelForShopSetup2(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('EUR');

        $product = $this->productRepository->get('simple');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getDeAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);

        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'DE',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');
        $this->validator->performAllGeneralChecks($response['body'], $quote);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_de_postal_13055.php
     *
     * @magentoConfigFixture current_store general/country/default DE
     * @magentoConfigFixture current_store general/store_information/country_id DE
     * @magentoConfigFixture current_store tax/defaults/country DE
     * @magentoConfigFixture current_store tax/calculation/price_includes_tax 1
     * @magentoConfigFixture current_store tax/calculation/shipping_includes_tax 1
     * @magentoConfigFixture current_store tax/calculation/discount_tax 1
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store shipping/origin/country_id DE
     * @magentoConfigFixture current_store shipping/origin/region_id 82
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteUsingCurrentQuoteOfGuestCustomerOnStoreLevelForShopSetup2(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('EUR');

        $product = $this->productRepository->get('simple');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getDeAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);

        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'DE',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');
        $this->validator->performAllGeneralChecks($response['body'], $quote);
    }

    public function testExecuteUsingCurrentQuoteLoggedInCustomerHasNoAddressInAccountForShopSetup2(): void
    {
        self::markTestSkipped('Integration testing framework has to be extended for customers to make it work');
    }

    public function testExecuteUsingNewQuoteLoggedInCustomerHasNoAddressInAccountForShopSetup2(): void
    {
        self::markTestSkipped('Integration testing framework has to be extended for customers to make it work');
    }

    public function testExecuteUsingCurrentQuoteLoggedInCustomerHasAddressInAccountForShopSetup2(): void
    {
        self::markTestSkipped('Integration testing framework has to be extended for customers to make it work');
    }

    public function testExecuteUsingNewQuoteLoggedInCustomerHasAddressInAccountForShopSetup2(): void
    {
        self::markTestSkipped('Integration testing framework has to be extended for customers to make it work');
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_virtual.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_de_postal_13055.php
     *
     * @magentoConfigFixture default/general/country/default DE
     * @magentoConfigFixture default/general/store_information/country_id DE
     * @magentoConfigFixture default/tax/defaults/country DE
     * @magentoConfigFixture default/tax/calculation/price_includes_tax 1
     * @magentoConfigFixture default/tax/calculation/shipping_includes_tax 1
     * @magentoConfigFixture default/tax/calculation/discount_tax 1
     * @magentoConfigFixture default/tax/display/shipping 2
     * @magentoConfigFixture default/tax/display/type 2
     * @magentoConfigFixture default/shipping/origin/country_id DE
     * @magentoConfigFixture default/shipping/origin/region_id 82
     * @magentoConfigFixture default/tax/display/shipping 2
     * @magentoConfigFixture default/tax/display/type 2
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteUsingCurrentVirtualQuoteOfGuestCustomerOnDefaultLevelForShopSetup2(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('EUR');

        $product = $this->productRepository->get('virtual-product');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getDeAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'DE',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');
        $orderLines = $response['body']['order_lines'];

        $this->validator->isKlarnaSumTotalsShopGrandTotalSame($orderLines, $quote);
        $this->validator->isKlarnaShopTaxSame($orderLines, $quote);
        $this->validator->isKlarnaProductTotalEqualUnitQty($orderLines);
        $this->validator->isKlarnaSumTotalsKlarnaOrderAmountSame($response['body']);
        $this->validator->isKlarnaSumTaxTotalsKlarnaOrderTaxAmountSame($response['body']);
        $this->validator->isKlarnaOrderAmountShopOrderAmountSame($response['body'], $quote);
        $this->validator->isKlarnaOrderTaxAmountShopTaxSame($response['body'], $quote);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_virtual.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_de_postal_13055.php
     *
     * @magentoConfigFixture current_store general/country/default DE
     * @magentoConfigFixture current_store general/store_information/country_id DE
     * @magentoConfigFixture current_store tax/defaults/country DE
     * @magentoConfigFixture current_store tax/calculation/price_includes_tax 1
     * @magentoConfigFixture current_store tax/calculation/shipping_includes_tax 1
     * @magentoConfigFixture current_store tax/calculation/discount_tax 1
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store shipping/origin/country_id DE
     * @magentoConfigFixture current_store shipping/origin/region_id 82
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteUsingCurrentVirtualQuoteOfGuestCustomerOnStoreLevelForShopSetup2(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('EUR');

        $product = $this->productRepository->get('virtual-product');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getDeAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'DE',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');
        $orderLines = $response['body']['order_lines'];

        $this->validator->isKlarnaSumTotalsShopGrandTotalSame($orderLines, $quote);
        $this->validator->isKlarnaShopTaxSame($orderLines, $quote);
        $this->validator->isKlarnaProductTotalEqualUnitQty($orderLines);
        $this->validator->isKlarnaSumTotalsKlarnaOrderAmountSame($response['body']);
        $this->validator->isKlarnaSumTaxTotalsKlarnaOrderTaxAmountSame($response['body']);
        $this->validator->isKlarnaOrderAmountShopOrderAmountSame($response['body'], $quote);
        $this->validator->isKlarnaOrderTaxAmountShopTaxSame($response['body'], $quote);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_us_postal_36104.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteReturningAuthCallUrlInMerchantUrlsFieldOnDefaultLevel(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('USD');

        $product = $this->productRepository->get('simple');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getUsAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'US',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');

        $body = $response['body'];
        static::assertTrue(isset($body['merchant_urls']['authorization']));
        static::assertNotEmpty(isset($body['merchant_urls']['authorization']));
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/tax_rule_us_postal_36104.php
     *
     * @magentoConfigFixture current_store general/country/default US
     * @magentoConfigFixture current_store general/store_information/country_id US
     * @magentoConfigFixture current_store general/store_information/region_id 82
     * @magentoConfigFixture current_store tax/defaults/country US
     * @magentoConfigFixture current_store tax/calculation/price_includes_tax 0
     * @magentoConfigFixture current_store tax/calculation/shipping_includes_tax 0
     * @magentoConfigFixture current_store tax/calculation/discount_tax 0
     * @magentoConfigFixture current_store tax/display/shipping 2
     * @magentoConfigFixture current_store tax/display/type 2
     * @magentoConfigFixture current_store shipping/origin/country_id US
     * @magentoConfigFixture current_store shipping/origin/region_id 1
     * @magentoConfigFixture current_store tax/display/shipping 1
     * @magentoConfigFixture current_store tax/display/type 1
     * @magentoConfigFixture default/currency/options/base USD
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     *
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteReturningAuthCallUrlInMerchantUrlsFieldOnStoreLevel(): void
    {
        $quote = $this->session->getQuote();
        $quote->setBaseCurrencyCode('USD');

        $product = $this->productRepository->get('simple');
        $quote->addProduct($product);

        /** @var AddressInterface $address */
        $address = $this->dataProvider->getUsAddressData();
        $quote->getBillingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setCountryId($address[AddressInterface::KEY_COUNTRY_ID]);
        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');

        $quote->setTotalsCollectedFlag(false);
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->collectTotals();
        $quote->save();

        $params = [
            'additional_input' => json_encode([
                'use_existing_quote' => '1',
                'auth_callback_token' => 'my_auth_callback_token'
            ]),
            'country_id' => 'US',
            'shipping_method' => 'flatrate',
            'shipping_carrier_code' => 'flatrate'
        ];

        $response = $this->sendRequest($params, 'kec/klarna/getPayLoad', 'POST');

        $body = $response['body'];
        static::assertTrue(isset($body['merchant_urls']['authorization']));
        static::assertNotEmpty(isset($body['merchant_urls']['authorization']));
    }
}