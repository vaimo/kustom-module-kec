<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Kec\Block;

/**
 * @internal
 */
class Footer extends BaseAbstract
{
    /**
     * Getting back the API configuration instance
     *
     * @return string
     */
    public function getJsUrl(): string
    {
        return 'https://x.klarnacdn.net/kp/lib/v1/api.js';
    }
}
