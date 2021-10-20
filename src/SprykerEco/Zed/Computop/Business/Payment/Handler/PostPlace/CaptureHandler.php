<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Handler\PostPlace;

use Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer;
use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class CaptureHandler extends AbstractHandler
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $computopApiHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer
     */
    public function handle(OrderTransfer $orderTransfer, ComputopApiHeaderPaymentTransfer $computopApiHeaderPayment)
    {
        $responseTransfer = $this->performRequest($orderTransfer, $computopApiHeaderPayment);

        $this->saver->save($responseTransfer, $orderTransfer);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $computopApiHeaderPayment
     *
     * @return \Generated\Shared\Transfer\ComputopApiCaptureResponseTransfer
     */
    protected function performRequest(
        OrderTransfer $orderTransfer,
        ComputopApiHeaderPaymentTransfer $computopApiHeaderPayment
    ): ComputopApiCaptureResponseTransfer {
        $responseTransfer = $this->computopApiFacade->performInquireRequest($orderTransfer, $computopApiHeaderPayment);
        if ($responseTransfer->getIsCapLast() && $responseTransfer->getAmountCap() === $responseTransfer->getAmountAuth()) {
            return (new ComputopApiCaptureResponseTransfer())
                ->setHeader($responseTransfer->getHeader())
                ->setAmount($responseTransfer->getAmountCap())
                ->setErrorText($responseTransfer->getHeader()->getCode())
                ->setTransactionId($responseTransfer->getHeader()->getTransId());
        }

        return $this
            ->computopApiFacade
            ->performCaptureRequest($orderTransfer, $computopApiHeaderPayment);
    }
}
