<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

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
     * @inheritdoc
     */
    public function getComputopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->computopService->getComputopMacHashHmacValue($cardPaymentTransfer);
    }

    /**
     * @inheritdoc
     */
    public function extractHeader(array $decryptedArray)
    {
        return $this->computopService->extractHeader($decryptedArray);
    }

    /**
     * @inheritdoc
     */
    public function getDecryptedArray(array $responseArray, $password)
    {
        return $this->computopService->getDecryptedArray($responseArray, $password);
    }

    /**
     * @inheritdoc
     */
    public function getEncryptedArray(array $dataSubArray, $password)
    {
        return $this->computopService->getEncryptedArray($dataSubArray, $password);
    }

}
