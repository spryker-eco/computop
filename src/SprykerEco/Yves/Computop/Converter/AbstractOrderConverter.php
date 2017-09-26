<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;

abstract class AbstractOrderConverter implements ConverterInterface
{

    /**
     * @param array $formattedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    abstract protected function createFormattedResponseTransfer(array $formattedArray, ComputopResponseHeaderTransfer $header);

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $formattedArray = $this->formatResponseArray($decryptedArray);

        return $this->createFormattedResponseTransfer($formattedArray, $header);
    }

    /**
     * Computop returns keys in different formats. F.e. "BIC", "bic".
     * Function returns keys in one unique format.
     *
     * @param array $decryptedArray
     *
     * @return array
     */
    protected function formatResponseArray(array $decryptedArray)
    {
        $formattedArray = [];

        foreach ($decryptedArray as $key => $value) {
            $formattedArray[$this->formatString($key)] = $value;
        }

        return $formattedArray;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function formatString($string)
    {
        return strtolower($string);
    }

    /**
     * @param array $responseArray
     * @param string $key
     *
     * @return null|string
     */
    protected function getValue(array $responseArray, $key)
    {
        if (isset($responseArray[$this->formatString($key)])) {
            return $responseArray[$this->formatString($key)];
        }

        return null;
    }

}
