<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

interface ConverterInterface
{
    /**
     * @param array $decryptedArray
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getResponseTransfer(array $decryptedArray);
}
