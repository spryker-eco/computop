<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Reader;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
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
    public function isComputopPaymentExist(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $paymentTransfer */
        $paymentTransfer = $this->getPaymentTransfer($quoteTransfer);
        $paymentEntity = $this->queryContainer->queryPaymentByTransactionId($paymentTransfer->getTransIdOrFail())->findOne();
        if (isset($paymentEntity)) {
            $quoteTransfer->getPaymentOrFail()->setIsComputopPaymentExist(true);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getPaymentTransfer(QuoteTransfer $quoteTransfer): TransferInterface
    {
        $paymentTransfer = $quoteTransfer->getPayment();
        $paymentSelection = $paymentTransfer->getPaymentSelection();
        $method = 'get' . ucfirst($paymentSelection);

        return $paymentTransfer->$method();
    }
}
