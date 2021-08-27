<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\RiskCheck\Saver;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog;
use SprykerEco\Zed\Computop\Business\Logger\LoggerInterface;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractSaver implements RiskCheckSaverInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Logger\LoggerInterface
     */
    protected $logger;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Computop\Business\Logger\LoggerInterface $logger
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(
        ComputopQueryContainerInterface $queryContainer,
        LoggerInterface $logger,
        ComputopConfig $config
    ) {
        $this->queryContainer = $queryContainer;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $headerTransfer
     * @param string $method
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopApiLog
     */
    protected function logHeader(ComputopApiResponseHeaderTransfer $headerTransfer, $method): SpyPaymentComputopApiLog
    {
        return $this->logger->log($headerTransfer, $method);
    }
}
