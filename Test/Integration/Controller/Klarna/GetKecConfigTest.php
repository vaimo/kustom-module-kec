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
class GetKecConfigTest extends ControllerTestCase
{
    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 1
     * @magentoConfigFixture current_store payment/kec/position cart,product,mini_cart
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsResponse(): void
    {
        $statusCodeOK = 200;

        $response = $this->sendGetKecConfigRequest(false);

        static::assertTrue($response['statusCode'] == $statusCodeOK);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 1
     * @magentoConfigFixture current_store payment/kec/position cart,product,mini_cart
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsAuthCallbackToken(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertTrue(isset($response['authCallbackToken']));
        static::assertTrue(!empty($response['authCallbackToken']));
        static::assertEquals(32, strlen($response['authCallbackToken']));
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 1
     * @magentoConfigFixture current_store payment/kec/position cart,product,mini_cart
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsIsShowable(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertTrue(isset($response['isShowable']));
        static::assertTrue($response['isShowable']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 1
     * @magentoConfigFixture current_store payment/kec/position cart,product,mini_cart
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsClientId(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertTrue(isset($response['clientId']));
        static::assertEquals('', $response['clientId']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 1
     * @magentoConfigFixture current_store payment/kec/position cart,product,mini_cart
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsTheme(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertTrue(isset($response['theme']));
        static::assertEquals('dark', $response['theme']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 1
     * @magentoConfigFixture current_store payment/kec/position cart,product,mini_cart
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsShape(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertTrue(isset($response['shape']));
        static::assertEquals('rounded', $response['shape']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 1
     * @magentoConfigFixture current_store payment/kec/position cart,product,mini_cart
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsUSLocale(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertTrue(isset($response['locale']));
        static::assertEquals('en-US', $response['locale']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 1
     * @magentoConfigFixture current_store payment/kec/position cart,product,mini_cart
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
     *
     * @magentoConfigFixture current_store general/country/default DE
     * @magentoConfigFixture current_store general/locale/code de_DE
     * @magentoConfigFixture current_store general/store_information/country_id DE
     * @magentoConfigFixture current_store currency/options/default EUR
     * @magentoConfigFixture current_store currency/options/allow EUR
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
     * @magentoConfigFixture current_store currency/options/base EUR
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsDELocale(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertTrue(isset($response['locale']));
        static::assertEquals('de-DE', $response['locale']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 0
     * @magentoConfigFixture current_store payment/kec/position cart,product
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsIsShowableFalse(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertFalse($response['isShowable']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 0
     * @magentoConfigFixture current_store payment/kec/position cart,product
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsNoClientId(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertSame('', $response['clientId']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 0
     * @magentoConfigFixture current_store payment/kec/position cart,product
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsNoShape(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertSame('', $response['shape']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 0
     * @magentoConfigFixture current_store payment/kec/position cart,product
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsNoTheme(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertSame('', $response['theme']);
    }

    /**
     * @magentoConfigFixture current_store payment/klarna_kp/active 1
     *
     * @magentoConfigFixture current_store payment/kec/enabled 0
     * @magentoConfigFixture current_store payment/kec/position cart,product
     * @magentoConfigFixture current_store payment/kec/theme dark
     * @magentoConfigFixture current_store payment/kec/shape rounded
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
     * @magentoConfigFixture current_store currency/options/default USD
     * @magentoConfigFixture current_store currency/options/allow USD
     * @magentoConfigFixture current_store general/locale/code en_US
     * @magentoConfigFixture current_store tax/classes/shipping_tax_class 2
     *
     * @magentoConfigFixture current_store currency/options/base USD
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     */
    public function testExecuteGetKecConfigReturnsNoLocale(): void
    {
        $response = $this->sendGetKecConfigRequest();

        static::assertSame('', $response['locale']);
    }

    /**
     * Sends a GET request to the getKecConfig endpoint. If body flag is false, return response
     *
     * @param bool $bodyFlag
     * @return array|mixed
     */
    private function sendGetKecConfigRequest(bool $bodyFlag = true): mixed
    {
        $response = $this->sendRequest([], 'kec/klarna/getKecConfig', 'GET');
        if ($bodyFlag) {
            return $response['body'];
        }
        return $response;
    }
}