<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ConverterInterface
{
    /**
     * @param array $decryptedArray
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getResponseTransfer(array $decryptedArray): TransferInterface;
}
