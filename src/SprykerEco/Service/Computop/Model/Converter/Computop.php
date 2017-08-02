<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Converter;

use Generated\Shared\Transfer\ComputopCreditCardResponseTransfer;
use SprykerEco\Service\Computop\Model\AbstractComputop;

class Computop extends AbstractComputop implements ComputopInterface
{

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

}
