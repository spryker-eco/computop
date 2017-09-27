<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
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
     * @param array $items
     *
     * @return string
     */
    public function getTestModeDescriptionValue(array $items)
    {
        return $this->computopService->getTestModeDescriptionValue($items);
    }

    /**
     * @param array $items
     *
     * @return string
     */
    public function getDescriptionValue(array $items)
    {
        return $this->computopService->getDescriptionValue($items);
    }

    /**
     * @param array $responseArray
     * @param string $key
     *
     * @return null|string
     */
    public function getResponseValue(array $responseArray, $key)
    {
        return $this->computopService->getResponseValue($responseArray, $key);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(TransferInterface $cardPaymentTransfer)
    {
        return $this->computopService->getMacEncryptedValue($cardPaymentTransfer);
    }

    /**
     * @param array $decryptedArray
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader(array $decryptedArray, $method)
    {
        return $this->computopService->extractHeader($decryptedArray, $method);
    }

    /**
     * @param array $responseArray
     * @param string $password
     *
     * @return array
     */
    public function getDecryptedArray(array $responseArray, $password)
    {
        return $this->computopService->getDecryptedArray($responseArray, $password);
    }

    /**
     * @param array $dataSubArray
     * @param string $password
     *
     * @return array
     */
    public function getEncryptedArray(array $dataSubArray, $password)
    {
        return $this->computopService->getEncryptedArray($dataSubArray, $password);
    }

}
