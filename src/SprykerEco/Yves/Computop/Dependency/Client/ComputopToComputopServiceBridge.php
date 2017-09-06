<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
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
    public function getDescriptionValue(array $items)
    {
        return $this->computopService->getDescriptionValue($items);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(AbstractTransfer $cardPaymentTransfer)
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
