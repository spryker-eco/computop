<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order\PrePlace;

use DateTime;
use Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

class DirectDebitMapper extends AbstractPrePlaceMapper
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
        $dataSubArray[ComputopFieldName::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopFieldName::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopFieldName::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopFieldName::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopFieldName::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopFieldName::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopFieldName::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopFieldName::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopFieldName::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopFieldName::ETI_ID] = ComputopConfig::ETI_ID;
        $dataSubArray[ComputopFieldName::MANDATE_ID] = $cardPaymentTransfer->getMandateId();
        $dataSubArray[ComputopFieldName::DATE_OF_SIGNATURE_ID] = $this->getDateOfSignature();

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
        return Config::get(ComputopConstants::DIRECT_DEBIT_ORDER_ACTION);
    }
}