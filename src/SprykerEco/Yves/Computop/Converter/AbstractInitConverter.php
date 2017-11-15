<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\Computop\ComputopServiceInterface;
use SprykerEco\Shared\Computop\ComputopConfig;

abstract class AbstractInitConverter implements ConverterInterface
{
    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;
    
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
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     * @param \SprykerEco\Yves\Computop\ComputopConfig $config
     */
    public function __construct(ComputopServiceInterface $computopService, $config)
    {
        $this->computopService = $computopService;
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
            ->computopService
            ->getDecryptedArray($responseArray, $this->config->getBlowfishPassword());

        $responseHeaderTransfer = $this
            ->computopService
            ->extractHeader(
                $decryptedArray,
                ComputopConfig::INIT_METHOD
            );

        return $this->createResponseTransfer($decryptedArray, $responseHeaderTransfer);
    }
}
