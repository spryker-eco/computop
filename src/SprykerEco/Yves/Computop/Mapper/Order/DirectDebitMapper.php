<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order;

use DateTime;
use Generated\Shared\Transfer\ComputopDirectDebitPaymentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
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
        $computopPaymentTransfer->setMerchantId(Config::get(ComputopConstants::COMPUTOP_MERCHANT_ID));
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $computopPaymentTransfer->setCapture(ComputopConstants::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setResponse(ComputopConstants::RESPONSE_TYPE);
        $computopPaymentTransfer->setTxType(ComputopConstants::TX_TYPE_ORDER);

        $computopPaymentTransfer->setMandateId($computopPaymentTransfer->getTransId());

        $computopPaymentTransfer->setUrlSuccess(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::DIRECT_DEBIT_SUCCESS_PATH_NAME))
        );
        $computopPaymentTransfer->setUrlFailure(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::FAILURE_PATH_NAME))
        );

        $computopPaymentTransfer->setClientIp($this->getClientIp());
        $computopPaymentTransfer->setOrderDesc(
            $this->computopService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
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
        $dataSubArray[ComputopConstants::TRANS_ID_F_N] = $cardPaymentTransfer->getTransId();
        $dataSubArray[ComputopConstants::AMOUNT_F_N] = $cardPaymentTransfer->getAmount();
        $dataSubArray[ComputopConstants::CURRENCY_F_N] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[ComputopConstants::URL_SUCCESS_F_N] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[ComputopConstants::URL_FAILURE_F_N] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[ComputopConstants::CAPTURE_F_N] = $cardPaymentTransfer->getCapture();
        $dataSubArray[ComputopConstants::RESPONSE_F_N] = $cardPaymentTransfer->getResponse();
        $dataSubArray[ComputopConstants::MAC_F_N] = $cardPaymentTransfer->getMac();
        $dataSubArray[ComputopConstants::TX_TYPE_F_N] = $cardPaymentTransfer->getTxType();
        $dataSubArray[ComputopConstants::ORDER_DESC_F_N] = $cardPaymentTransfer->getOrderDesc();
        $dataSubArray[ComputopConstants::ETI_ID_F_N] = ComputopConstants::ETI_ID;
        $dataSubArray[ComputopConstants::MANDATE_ID_F_N] = $cardPaymentTransfer->getMandateId();
        $dataSubArray[ComputopConstants::DATE_OF_SIGNATURE_ID_F_N] = $this->getDateOfSignature();

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
     * TODO:remove after test if need
     *
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return string
     */
    protected function getUrlToComputop($merchantId, $data, $length)
    {
        return Config::get(ComputopConstants::COMPUTOP_DIRECT_DEBIT_ORDER_ACTION) . '?' . http_build_query([
                ComputopConstants::MERCHANT_ID_F_N => $merchantId,
                ComputopConstants::DATA_F_N => $data,
                ComputopConstants::LENGTH_F_N => $length,
            ]);
    }

}
