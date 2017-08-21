<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;

interface ComputopToComputopServiceInterface
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
    public function getMacEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

    /**
     * @param array $decryptedArray
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader($decryptedArray, $method);

    /**
     * @param array $responseArray
     * @param string $password
     *
     * @return array
     */
    public function getDecryptedArray($responseArray, $password);

    /**
     * @param array $dataSubArray
     * @param string $password
     *
     * @return array
     */
    public function getEncryptedArray($dataSubArray, $password);

}
