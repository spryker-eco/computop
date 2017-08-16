<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use SprykerEco\Service\Computop\ComputopServiceInterface;

class ComputopToComputopServiceBridge implements ComputopToComputopServiceInterface
{

    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     */
    public function __construct(ComputopServiceInterface $computopService)
    {
        $this->computopService = $computopService;
    }

    /**
     * @inheritdoc
     */
    public function getDescriptionValue(array $items)
    {
        return $this->computopService->getDescriptionValue($items);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getComputopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->computopService->getComputopMacHashHmacValue($cardPaymentTransfer);
    }

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader($decryptedArray)
    {
        return $this->computopService->extractHeader($decryptedArray);
    }

    /**
     * @param array $responseArray
     * @param string $password
     *
     * @return array
     */
    public function getDecryptedArray($responseArray, $password)
    {
        return $this->computopService->getDecryptedArray($responseArray, $password);
    }

    /**
     * @param array $dataSubArray
     * @param string $password
     *
     * @return array
     */
    public function getEncryptedArray($dataSubArray, $password)
    {
        return $this->computopService->getEncryptedArray($dataSubArray, $password);
    }

}
