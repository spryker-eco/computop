<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface ComputopToComputopServiceInterface
{

    /**
     * @param array $items
     *
     * @return string
     */
    public function getDescriptionValue(array $items);

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(AbstractTransfer $cardPaymentTransfer);

    /**
     * @param array $decryptedArray
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader(array $decryptedArray, $method);

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
