<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Init;

use Generated\Shared\Transfer\ComputopApiRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\ComputopApi\ComputopApiServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig;

abstract class AbstractMapper implements InitMapperInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface
     */
    protected $computopApiService;

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     * @param \SprykerEco\Service\ComputopApi\ComputopApiServiceInterface $computopApiService
     */
    public function __construct(
        ComputopConfig $config,
        ComputopApiServiceInterface $computopApiService
    ) {
        $this->config = $config;
        $this->computopApiService = $computopApiService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateComputopPaymentTransfer(QuoteTransfer $quoteTransfer, TransferInterface $computopPaymentTransfer)
    {
        $computopPaymentTransfer->setRefNr($quoteTransfer->getOrderReference());

        return $computopPaymentTransfer;
    }

    /**
     * @param string $actionUrl
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return string
     */
    protected function getUrlToComputop(string $actionUrl, string $merchantId, string $data, int $length)
    {
        return $actionUrl . '?' . http_build_query([
                ComputopApiConfig::MERCHANT_ID => $merchantId,
                ComputopApiConfig::DATA => $data,
                ComputopApiConfig::LENGTH => $length,
            ]);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiRequestTransfer
     */
    protected function createRequestTransfer(TransferInterface $computopPaymentTransfer)
    {
        return (new ComputopApiRequestTransfer())
            ->fromArray($computopPaymentTransfer->toArray(), true);
    }
}
