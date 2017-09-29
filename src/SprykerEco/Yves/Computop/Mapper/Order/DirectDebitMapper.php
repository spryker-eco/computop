<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order;

use DateTime;
use Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class DirectDebitMapper extends AbstractMapper
{

    const DATE_FORMAT = 'd.m.Y';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = new ComputopDirectDebitPaymentTransfer();

        $computopPaymentTransfer->setTransId($this->getTransId($quoteTransfer));
        $computopPaymentTransfer->setMandateId($computopPaymentTransfer->getTransId());
        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::DIRECT_DEBIT_SUCCESS_PATH_NAME))
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
        /** @var \Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer $cardPaymentTransfer */
        $dataSubArray[ComputopFieldNameConstants::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldNameConstants::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldNameConstants::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldNameConstants::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopFieldNameConstants::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopFieldNameConstants::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopFieldNameConstants::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopFieldNameConstants::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldNameConstants::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopFieldNameConstants::ETI_ID] = ComputopConfig::ETI_ID;
        $dataSubArray[ComputopFieldNameConstants::MANDATE_ID] = $cardPaymentTransfer->getMandateId();
        $dataSubArray[ComputopFieldNameConstants::DATE_OF_SIGNATURE_ID] = $this->getDateOfSignature();

        return $dataSubArray;
    }

    /**
     * @return string
     */
    protected function getDateOfSignature()
    {
        $now = new DateTime();

        return $now->format(self::DATE_FORMAT);
    }

    /**
     * @return string
     */
    protected function getActionUrl()
    {
        return Config::get(ComputopConstants::COMPUTOP_DIRECT_DEBIT_ORDER_ACTION);
    }

}
