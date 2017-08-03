<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractResponseHandler
{

    /**
     * @var \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface
     */
    protected $logger;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     */
    protected $queryContainer;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     * @param \SprykerEco\Zed\Computop\Business\Payment\Handler\Logger\ComputopResponseLoggerInterface $logger
     */
    public function __construct(
        ComputopQueryContainerInterface $queryContainer,
        ComputopResponseLoggerInterface $logger
    ) {
        $this->queryContainer = $queryContainer;
        $this->logger = $logger;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $headerTransfer
     * @param string $method
     *
     * @return \Orm\Zed\Computop\Persistence\Base\SpyPaymentComputopApiLog
     */
    protected function logHeader(ComputopResponseHeaderTransfer $headerTransfer, $method)
    {
        return $this->logger->log($headerTransfer, $method);
    }

}
