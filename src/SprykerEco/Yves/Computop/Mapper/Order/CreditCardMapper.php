<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class CreditCardMapper extends AbstractMapper
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->getTransId($quoteTransfer));
        $computopPaymentTransfer->setTxType(ComputopConstants::TX_TYPE_ORDER);
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::CREDIT_CARD_SUCCESS_PATH_NAME))
        );
        $computopPaymentTransfer->setOrderDesc(
            $this->computopService->getTestModeDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return array
     */
    protected function getDataSubArray(TransferInterface $cardPaymentTransfer)
    {
        /** @var \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer */
        $dataSubArray[ComputopFieldNameConstants::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldNameConstants::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldNameConstants::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldNameConstants::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopFieldNameConstants::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopFieldNameConstants::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopFieldNameConstants::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopFieldNameConstants::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldNameConstants::TX_TYPE] = $cardPaymentTransfer->getTxType();
        $dataSubArray[ComputopFieldNameConstants::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopFieldNameConstants::ETI_ID] = ComputopConstants::ETI_ID;

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_ORDER_ACTION);
    }

}
