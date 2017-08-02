<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopCreditCardResponseTransfer;

class Computop implements ComputopInterface
{

    const MAC_SEPARATOR = '*';
    const DATA_SEPARATOR = '&';
    const DATA_SUB_SEPARATOR = '=';

    const TRANS_ID = 'TransID';
    const AMOUNT = 'Amount';
    const CURRENCY = 'Currency';
    const URL_SUCCESS = 'URLSuccess';
    const URL_FAILURE = 'URLFailure';
    const CAPTURE = 'Capture';
    const RESPONSE = 'Response';
    const MAC = 'MAC';
    const TX_TYPE = 'TxType';
    const ORDER_DESC = 'OrderDesc';
    const PAY_ID = 'PayID';

    const MID = 'mid';
    const STATUS = 'Status';
    const DESCRIPTION = 'Description';
    const CODE = 'Code';
    const XID = 'XID';
    const TYPE = 'Type';
    const PCN_R = 'PCNr';
    const CC_EXPIRY = 'CCExpiry';
    const CC_BRAND = 'CCBrand';

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
     * @param string $responseEncryptedString
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer
     */
    public function getComputopResponseTransfer($responseEncryptedString)
    {
        $decryptedDataArray = $this->getResponseDecryptedArray($responseEncryptedString);
        $computopCreditCardResponseTransfer = $this->createComputopCreditCardResponseTransfer($decryptedDataArray);

        return $computopCreditCardResponseTransfer;
    }

    /**
     * @param string $decryptedString
     *
     * @return array
     */
    protected function getResponseDecryptedArray($decryptedString)
    {
        $decryptedDataArray = [];
        $decryptedDataSubArray = explode(self::DATA_SEPARATOR, $decryptedString);
        foreach ($decryptedDataSubArray as $value) {
            $data = explode(self::DATA_SUB_SEPARATOR, $value);
            $decryptedDataArray[array_shift($data)] = array_shift($data);
        }

        return $decryptedDataArray;
    }

    /**
     * @param array $decryptedDataArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer
     */
    protected function createComputopCreditCardResponseTransfer($decryptedDataArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardResponseTransfer();

        $computopCreditCardResponseTransfer->setMid($this->getParamOrNull($decryptedDataArray, self::MID));
        $computopCreditCardResponseTransfer->setPayId($this->getParamOrNull($decryptedDataArray, self::PAY_ID));
        $computopCreditCardResponseTransfer->setStatus($this->getParamOrNull($decryptedDataArray, self::STATUS));
        $computopCreditCardResponseTransfer->setDescription($this->getParamOrNull($decryptedDataArray, self::DESCRIPTION));
        $computopCreditCardResponseTransfer->setCode($this->getParamOrNull($decryptedDataArray, self::CODE));
        $computopCreditCardResponseTransfer->setXid($this->getParamOrNull($decryptedDataArray, self::XID));
        $computopCreditCardResponseTransfer->setTransId($this->getParamOrNull($decryptedDataArray, self::TRANS_ID));
        $computopCreditCardResponseTransfer->setType($this->getParamOrNull($decryptedDataArray, self::TYPE));
        $computopCreditCardResponseTransfer->setMac($this->getParamOrNull($decryptedDataArray, self::MAC));
        $computopCreditCardResponseTransfer->setPcnr($this->getParamOrNull($decryptedDataArray, self::PCN_R));
        $computopCreditCardResponseTransfer->setCCExpiry($this->getParamOrNull($decryptedDataArray, self::CC_EXPIRY));
        $computopCreditCardResponseTransfer->setCCBrand($this->getParamOrNull($decryptedDataArray, self::CC_BRAND));

        return $computopCreditCardResponseTransfer;
    }

    /**
     * @param array $decryptedDataArray
     * @param string $name
     *
     * @return string|null
     */
    protected function getParamOrNull($decryptedDataArray, $name)
    {
        return isset($decryptedDataArray[$name]) ? $decryptedDataArray[$name] : null;
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
