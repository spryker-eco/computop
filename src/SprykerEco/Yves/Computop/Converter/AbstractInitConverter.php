<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\ComputopConfig as ComputopComputopConfig;

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
     * @param array $responseParamsArray
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    abstract protected function createResponseTransfer(
        array $responseParamsArray,
        ComputopApiResponseHeaderTransfer $header
    ): TransferInterface;

    /**
     * @param \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface $computopApiService
     * @param \SprykerEco\Yves\Computop\ComputopConfig $config
     */
    public function __construct(ComputopApiServiceInterface $computopApiService, ComputopComputopConfig $config)
    {
        $this->computopApiService = $computopApiService;
        $this->config = $config;
    }

    /**
     * @param array $responseHeader
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getResponseTransfer(array $responseHeader): TransferInterface
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
