<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency;

interface ComputopToStoreInterface
{
    /**
     * @return string
     */
    public function getCurrencyIsoCode();
}
