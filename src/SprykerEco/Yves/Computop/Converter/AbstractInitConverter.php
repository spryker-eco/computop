<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Shared\Computop\ComputopConfig;

abstract class AbstractInitConverter implements ConverterInterface
{
    /**
     * @var \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    protected $computopApiService;
    
    /**
     * @var \SprykerEco\Yves\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    abstract protected function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header);
        
    /**
     * @param \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface $computopApiService
     * @param \SprykerEco\Yves\Computop\ComputopConfig $config
     */
    public function __construct(ComputopApiServiceInterface $computopApiService, $config)
    {
        $this->computopApiService = $computopApiService;
        $this->config = $config;
    }

    /**
     * @param array $responseArray
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getResponseTransfer(array $responseArray)
    {
        $decryptedArray = $this
            ->computopApiService
            ->getDecryptedArray($responseArray, $this->config->getBlowfishPassword());

        $responseHeaderTransfer = $this
            ->computopApiService
            ->extractHeader(
                $decryptedArray,
                ComputopConfig::INIT_METHOD
            );

        return $this->createResponseTransfer($decryptedArray, $responseHeaderTransfer);
    }
}
