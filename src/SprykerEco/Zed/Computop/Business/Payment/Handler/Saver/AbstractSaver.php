<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\Saver;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractSaver implements SaverInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface
     */
    protected $logger;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface $logger
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(
        ComputopQueryContainerInterface $queryContainer,
        ComputopResponseLoggerInterface $logger,
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
    protected function logHeader(ComputopApiResponseHeaderTransfer $headerTransfer, $method)
    {
        return $this->logger->log($headerTransfer, $method);
    }
}
