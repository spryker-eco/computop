<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapper;

abstract class AbstractCreditCardMapper extends AbstractMapper
{

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConstants::PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function getComputopPaymentTransfer(OrderTransfer $orderTransfer)
    {
        $computopPaymentTransfer = $this->createCardPaymentTransfer($orderTransfer);

        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createCardPaymentTransfer(OrderTransfer $orderTransfer)
    {
        $computopPaymentTransfer = new ComputopCreditCardPaymentTransfer();
        $computopPaymentTransfer->fromArray($this->savedComputopEntity->toArray(), true);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($this->getAmount($orderTransfer));
        $computopPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());

        return $computopPaymentTransfer;
    }

}
