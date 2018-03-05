<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Init;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\ComputopServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig;

abstract class AbstractMapper implements InitMapperInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     */
    public function __construct(ComputopConfig $config, ComputopServiceInterface $computopService)
    {
        $this->config = $config;
        $this->computopService = $computopService;
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
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return string
     */
    protected function getUrlToComputop($merchantId, $data, $length)
    {
        return $this->getActionUrl() . '?' . http_build_query([
                ComputopApiConfig::MERCHANT_ID => $merchantId,
                ComputopApiConfig::DATA => $data,
                ComputopApiConfig::LENGTH => $length,
            ]);
    }
}
