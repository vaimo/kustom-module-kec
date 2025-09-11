<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Model\Update\Address;

/**
 * @internal
 */
class Normalizer
{
    /**
     * Normalizing the Klarna address data
     *
     * @param array $address
     * @return array
     */
    public function normalizeKlarnaAddress(array $address): array
    {
        return $this->normalize(['phone', 'postal_code'], $address);
    }

    /**
     * Normalizing the address data
     *
     * @param array $address
     * @return array
     */
    public function normalizeShopAddress(array $address): array
    {
        return $this->normalize(['telephone'], $address);
    }

    /**
     * Normalizing the address and removing on specific entries all spaces
     *
     * @param array $keysTrimInvalidSpaces
     * @param array $address
     * @return array
     */
    private function normalize(array $keysTrimInvalidSpaces, array $address): array
    {
        foreach ($keysTrimInvalidSpaces as $key) {
            if (isset($address[$key])) {
                $address[$key] = preg_replace('/[\s+]/', '', $address[$key]);
            }
        }

        return array_map(function ($value) {
            return $value ?: '';
        }, $address);
    }
}
