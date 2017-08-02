<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Mapper;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use SprykerEco\Service\Computop\Model\AbstractComputop;

class Computop extends AbstractComputop implements ComputopInterface
{

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $macDataArray = [
            $cardPaymentTransfer->getPayId(),
            $cardPaymentTransfer->getTransId(),
            $cardPaymentTransfer->getMerchantId(),
            $cardPaymentTransfer->getAmount(),
            $cardPaymentTransfer->getCurrency(),
        ];

        return implode(self::MAC_SEPARATOR, $macDataArray);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getOrderDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[self::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[self::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[self::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[self::URL_SUCCESS] = $cardPaymentTransfer->getUrlSuccess();
        $dataSubArray[self::URL_FAILURE] = $cardPaymentTransfer->getUrlFailure();
        $dataSubArray[self::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[self::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[self::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[self::TX_TYPE] = $cardPaymentTransfer->getTxType();
        $dataSubArray[self::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();

        $dataArray = $this->getDataEncryptedArray($dataSubArray);

        return implode(self::DATA_SEPARATOR, $dataArray);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getAuthorizationDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $dataSubArray[self::PAY_ID] = $cardPaymentTransfer->getPayId();
        $dataSubArray[self::TRANS_ID] = $cardPaymentTransfer->getTransId();
        $dataSubArray[self::AMOUNT] = $cardPaymentTransfer->getAmount();
        $dataSubArray[self::CURRENCY] = $cardPaymentTransfer->getCurrency();
        $dataSubArray[self::CAPTURE] = $cardPaymentTransfer->getCapture();
        $dataSubArray[self::RESPONSE] = $cardPaymentTransfer->getResponse();
        $dataSubArray[self::MAC] = $cardPaymentTransfer->getMac();
        $dataSubArray[self::ORDER_DESC] = $cardPaymentTransfer->getOrderDesc();

        $dataArray = $this->getDataEncryptedArray($dataSubArray);

        return implode(self::DATA_SEPARATOR, $dataArray);
    }

    /**
     * @param array $dataSubArray
     *
     * @return array
     */
    protected function getDataEncryptedArray($dataSubArray)
    {
        $dataArray = [];
        foreach ($dataSubArray as $key => $value) {
            $dataArray[] = implode(self::DATA_SUB_SEPARATOR, [$key, $value]);
        }

        return $dataArray;
    }

}
