<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

class PaymentHydrator
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function hydratePaymentTransfer(
        TransferInterface $computopPaymentTransfer,
        ComputopHeaderPaymentTransfer $computopHeaderPayment
    ) {
        $paymentEntity = $this->queryContainer
            ->queryPaymentByTransactionId($computopHeaderPayment->getTransId())
            ->findOne();

        $computopPaymentTransfer->setReqId($paymentEntity->getReqId());
        $computopPaymentTransfer->setRefNr($paymentEntity->getReference());

        return $computopPaymentTransfer;
    }
}