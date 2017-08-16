<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;

interface ComputopServiceInterface
{

    /**
     * @param array $items
     *
     * @return string
     */
    public function getDescriptionValue(array $items);

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getComputopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader(array $decryptedArray);

    /**
     * @param array $responseArray
     * @param string $password
     *
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     *
     * @return array
     */
    public function getDecryptedArray(array $responseArray, $password);

    /**
     * @param array $dataSubArray
     * @param string $password
     *
     * @return array
     */
    public function getEncryptedArray(array $dataSubArray, $password);

    /**
     * @param string $value
     *
     * @return string
     */
    public function getHashHmacValue($value);

    /**
     * @param string $plaintext
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function getBlowfishEncryptedValue($plaintext, $len, $password);

    /**
     * @param string $cipher
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function getBlowfishDecryptedValue($cipher, $len, $password);

}
