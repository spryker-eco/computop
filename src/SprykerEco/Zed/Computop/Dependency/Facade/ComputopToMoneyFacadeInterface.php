<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

interface ComputopToMoneyFacadeInterface
{
    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal(int $value): float;

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger(float $value): int;
}
