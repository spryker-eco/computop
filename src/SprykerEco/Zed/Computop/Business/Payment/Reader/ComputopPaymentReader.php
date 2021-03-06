<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

class ComputopPaymentReader implements ComputopPaymentReaderInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     */
    public function __construct(ComputopQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function isComputopPaymentExist(QuoteTransfer $quoteTransfer)
    {
        $paymentTransfer = $this->getPaymentTransfer($quoteTransfer);
        $paymentEntity = $this->queryContainer->queryPaymentByTransactionId($paymentTransfer->getTransId())->findOne();
        if (isset($paymentEntity)) {
            $quoteTransfer->getPayment()->setIsComputopPaymentExist(true);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        $paymentSelection = $quoteTransfer->getPayment()->getPaymentSelection();
        $method = 'get' . ucfirst($paymentSelection);

        return $quoteTransfer->getPayment()->$method();
    }
}
