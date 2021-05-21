<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency;

interface ComputopToStoreInterface
{
    /**
     * @return string
     */
    public function getCurrencyIsoCode(): string;

    /**
     * @return string
     */
    public function getCurrentLanguage(): string;
}
