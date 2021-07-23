<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
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
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    abstract protected function createResponseTransfer(array $decryptedArray, ComputopApiResponseHeaderTransfer $header);

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
     * @param array $responseHeader
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getResponseTransfer(array $responseHeader)
    {
        $decryptedArray = $this
            ->computopApiService
            ->decryptResponseHeader($responseHeader, $this->config->getBlowfishPassword());

        $responseHeaderTransfer = $this
            ->computopApiService
            ->extractResponseHeader(
                $decryptedArray,
                ComputopConfig::INIT_METHOD
            );

        return $this->createResponseTransfer($decryptedArray, $responseHeaderTransfer);
    }
}
