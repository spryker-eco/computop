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
    public function getDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $transID = "TransID" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getTransId();
        $amount = "Amount" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getAmount();
        $currency = "Currency" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getCurrency();
        $urlSuccess = "URLSuccess" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getUrlSuccess();
        $urlFailure = "URLFailure" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getUrlFailure();
        $capture = "Capture" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getCapture();
        $response = "Response" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getResponse();
        $mac = "MAC" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getMac();
        $txType = "TxType" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getTxType();
        $orderDesc = "OrderDesc" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getOrderDesc();

        $query = [$transID, $amount, $currency, $urlSuccess, $urlFailure, $capture, $response, $mac, $txType, $orderDesc];

        return implode(self::DATA_SEPARATOR, $query);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getAuthorizationDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        $payId = "PayID" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getPayId();
        $transID = "TransID" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getTransId();
        $amount = "Amount" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getAmount();
        $currency = "Currency" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getCurrency();
        $capture = "Capture" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getCapture();
        $response = "Response" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getResponse();
        $mac = "MAC" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getMac();
        $orderDesc = "OrderDesc" . self::DATA_SUB_SEPARATOR . $cardPaymentTransfer->getOrderDesc();

        $query = [$payId, $transID, $amount, $currency, $capture, $response, $mac, $orderDesc];

        return implode(self::DATA_SEPARATOR, $query);
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

        $computopCreditCardResponseTransfer->setMid($this->getParamOrNull($decryptedDataArray, 'mid'));
        $computopCreditCardResponseTransfer->setPayId($this->getParamOrNull($decryptedDataArray, 'PayID'));
        $computopCreditCardResponseTransfer->setStatus($this->getParamOrNull($decryptedDataArray, 'Status'));
        $computopCreditCardResponseTransfer->setDescription($this->getParamOrNull($decryptedDataArray, 'Description'));
        $computopCreditCardResponseTransfer->setCode($this->getParamOrNull($decryptedDataArray, 'Code'));
        $computopCreditCardResponseTransfer->setXid($this->getParamOrNull($decryptedDataArray, 'XID'));
        $computopCreditCardResponseTransfer->setTransId($this->getParamOrNull($decryptedDataArray, 'TransID'));
        $computopCreditCardResponseTransfer->setType($this->getParamOrNull($decryptedDataArray, 'Type'));
        $computopCreditCardResponseTransfer->setMac($this->getParamOrNull($decryptedDataArray, 'MAC'));
        $computopCreditCardResponseTransfer->setPcnr($this->getParamOrNull($decryptedDataArray, 'PCNr'));
        $computopCreditCardResponseTransfer->setCCExpiry($this->getParamOrNull($decryptedDataArray, 'CCExpiry'));
        $computopCreditCardResponseTransfer->setCCBrand($this->getParamOrNull($decryptedDataArray, 'CCBrand'));

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

}
