<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Kec\Test\Unit\Model\Update\Address;

use Klarna\Base\Test\Unit\Mock\TestCase;
use Klarna\Kec\Model\Update\Address\Normalizer;

/**
 * @coversDefaultClass \Klarna\Kec\Model\Update\Address\Normalizer
 */
class NormalizerTest extends TestCase
{
    /**
     * @var Normalizer
     */
    private Normalizer $model;

    public function testNormalizeKlarnaAddressPhoneNumberHasSpace(): void
    {
        $input = [
            'phone' => '123 456 789 ',
            'postal_code' => ''
        ];

        $result = $this->model->normalizeKlarnaAddress(
            $input
        );
        static::assertEquals('123456789', $result['phone']);
    }

    public function testNormalizeKlarnaAddressPhoneNumberHasLeadingPlus(): void
    {
        $input = [
            'phone' => '+123456789 ',
            'postal_code' => ''
        ];

        $result = $this->model->normalizeKlarnaAddress(
            $input
        );
        static::assertEquals('123456789', $result['phone']);
    }

    public function testNormalizeKlarnaAddressOneEntryIsNull(): void
    {
        $input = [
            'phone' => '123456789',
            'postal_code' => '',
            'firstname' => null
        ];

        $result = $this->model->normalizeKlarnaAddress(
            $input
        );
        static::assertEquals('', $result['firstname']);
    }

    public function testNormalizeKlarnaAddressPostalCodeHasSpace(): void
    {
        $input = [
            'postal_code' => ' 11 22 3 ',
            'phone' => ''
        ];

        $result = $this->model->normalizeKlarnaAddress(
            $input
        );
        static::assertEquals('11223', $result['postal_code']);
    }

    public function testNormalizeShopAddressEmptyAddressImpliesReturningEmptyAddress(): void
    {
        $input = [];

        $result = $this->model->normalizeShopAddress(
            $input
        );
        static::assertSame($input, $result);
    }

    public function testNormalizeShopAddressTelephoneHasSpaceImpliesRemovedSpacesOnTheTelephone(): void
    {
        $input = [
            'telephone' => '123 456 789 ',
        ];

        $result = $this->model->normalizeShopAddress(
            $input
        );
        static::assertEquals('123456789', $result['telephone']);
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Normalizer::class);
    }
}
