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

/**
 * @internal
 */
class UpdateQuoteAddressTest extends ControllerTestCase
{

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteAddressesNodeMissingImpliesReturning400ErrorCodeForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        unset($params['addresses']);
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(400, $response['statusCode']);
        static::assertEquals('addresses key is missing', $response['body']['error_message']);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteAddressIsInvalidJsonImpliesReturning400ErrorCodeForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        $params['addresses'] = '{]';
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(400, $response['statusCode']);
        static::assertEquals('Invalid JSON for key addresses', $response['body']['error_message']);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteClientTokenNodeMissingImpliesReturning400ErrorCodeForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        unset($params['client_token']);
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(400, $response['statusCode']);
        static::assertEquals('client_token key is missing', $response['body']['error_message']);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteSessionIdNodeMissingImpliesReturning400ErrorCodeForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        unset($params['session_id']);
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(400, $response['statusCode']);
        static::assertEquals('session_id key is missing', $response['body']['error_message']);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteAdditionalInputNodeMissingImpliesReturning400ErrorCodeForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        unset($params['additional_input']);
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(400, $response['statusCode']);
        static::assertEquals('additional_input key is missing', $response['body']['error_message']);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteAdditionalInputIsInvalidJsonImpliesReturning400ErrorCodeForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        $params['additional_input'] = '{]';
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(400, $response['statusCode']);
        static::assertEquals('Invalid JSON for key additional_input', $response['body']['error_message']);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteProductNodeMissingImpliesReturning400ErrorCodeForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        unset($params['product']);
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(400, $response['statusCode']);
        static::assertEquals('Product ID is missing', $response['body']['error_message']);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteQtyNodeMissingImpliesReturning400ErrorCodeForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        unset($params['qty']);
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(400, $response['statusCode']);
        static::assertEquals('Qty is missing', $response['body']['error_message']);
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteAndGuestAllDataThereImpliesCheckingShippingAddressInQuoteForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(200, $response['statusCode']);

        $quote = $this->session->getQuote();
        $shippingAddressQuote = $quote->getShippingAddress();

        $addressKlarna = json_decode($params['addresses'], true);
        $shippingAddressKlarna = $addressKlarna['shipping_address'];
        static::assertEquals($shippingAddressKlarna['email'], $shippingAddressQuote->getEmail());
        static::assertEquals($shippingAddressKlarna['given_name'], $shippingAddressQuote->getFirstname());
        static::assertEquals($shippingAddressKlarna['family_name'], $shippingAddressQuote->getLastname());
        static::assertEquals($shippingAddressKlarna['street_address'], implode('', $shippingAddressQuote->getStreet()));
        static::assertEquals($shippingAddressKlarna['city'], $shippingAddressQuote->getCity());
        static::assertEquals($shippingAddressKlarna['postal_code'], $shippingAddressQuote->getPostCode());
        static::assertEquals($shippingAddressKlarna['country'], $shippingAddressQuote->getCountryId());
        static::assertEquals($shippingAddressKlarna['phone'], $shippingAddressQuote->getTelephone());
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteAndGuestAllDataThereButPostCodeHasSpacesImpliesCorrectPostalCodeSavedForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        $addressKlarna = json_decode($params['addresses'], true);
        $addressKlarna['shipping_address']['postal_code'] = ' 123 45 ';
        $params['addresses'] = json_encode($addressKlarna);
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(200, $response['statusCode']);

        $quote = $this->session->getQuote();
        $shippingAddressQuote = $quote->getShippingAddress();
        static::assertEquals('12345', $shippingAddressQuote->getPostCode());
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteAndGuestAllDataThereImpliesCheckingBillingAddressInQuoteForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(200, $response['statusCode']);

        $quote = $this->session->getQuote();
        $billingAddressQuote = $quote->getBillingAddress();

        $addressKlarna = json_decode($params['addresses'], true);
        $shippingAddressKlarna = $addressKlarna['shipping_address'];
        static::assertEquals($shippingAddressKlarna['email'], $billingAddressQuote->getEmail());
        static::assertEquals($shippingAddressKlarna['given_name'], $billingAddressQuote->getFirstname());
        static::assertEquals($shippingAddressKlarna['family_name'], $billingAddressQuote->getLastname());
        static::assertEquals($shippingAddressKlarna['street_address'], implode('', $billingAddressQuote->getStreet()));
        static::assertEquals($shippingAddressKlarna['city'], $billingAddressQuote->getCity());
        static::assertEquals($shippingAddressKlarna['postal_code'], $billingAddressQuote->getPostCode());
        static::assertEquals($shippingAddressKlarna['country'], $billingAddressQuote->getCountryId());
        static::assertEquals($shippingAddressKlarna['phone'], $billingAddressQuote->getTelephone());
    }

    /**
     * @magentoDataFixture Klarna_Base::Test/Integration/_files/fixtures/product_simple.php
     *
     * @magentoConfigFixture default/tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @group Kec_GetPayload
     */
    public function testExecuteForNewQuoteAndGuestAllDataThereImpliesProductIsAddedToQuoteForShopSetup1(): void
    {
        $params = $this->getFullInputForUsingNewQuote();
        $response = $this->sendRequest($params, 'kec/klarna/updateQuoteAddress', 'POST');

        static::assertEquals(200, $response['statusCode']);

        $quote = $this->session->getQuote();
        static::assertEquals('15.0000', $quote->getGrandTotal());
    }

    private function getFullInputForUsingNewQuote(): array
    {
        return [
            'session_id' => 'abc',
            'client_token' => 'def',
            'additional_input' => json_encode([
                'use_existing_quote' => 0,
                'auth_callback_token' => 'ghi'
            ]),
            'addresses' => json_encode([
                'shipping_address' => $this->dataProvider->getUsKlarnaAddressData()
            ]),
            'product' => '99999',
            'qty' => 1
        ];
    }
}
