<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ComputopToComputopServiceInterface
{
    /**
     * @param array $items
     *
     * @return string
     */
    public function getTestModeDescriptionValue(array $items);

    /**
     * @param array $items
     *
     * @return string
     */
    public function getDescriptionValue(array $items);

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(TransferInterface $cardPaymentTransfer);

    /**
     * @param array $decryptedArray
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader(array $decryptedArray, $method);

    /**
     * @param array $responseArray
     * @param string $key
     *
     * @return null|string
     */
    public function getResponseValue(array $responseArray, $key);

    /**
     * @param array $responseArray
     * @param string $password
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
}
