<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\Computop\Exception\ComputopConverterException;
use SprykerEco\Service\Computop\Model\AbstractComputop;
use SprykerEco\Shared\Computop\ComputopConstants;

class Computop extends AbstractComputop implements ComputopInterface
{

    /**
     * @inheritdoc
     */
    public function extractHeader($decryptedArray, $method)
    {
        $this->checkDecryptedResponse($decryptedArray);

        $header = new ComputopResponseHeaderTransfer();

        $header->fromArray($decryptedArray, true);

        //different naming style
        $header->setMId($decryptedArray[ComputopConstants::MID_F_N]);
        $header->setTransId($decryptedArray[ComputopConstants::TRANS_ID_F_N]);
        $header->setPayId($decryptedArray[ComputopConstants::PAY_ID_F_N]);
        $header->setIsSuccess($header->getStatus() === ComputopConstants::SUCCESS_STATUS);
        $header->setMethod($method);

        //optional
        $header->setMac(isset($decryptedArray[ComputopConstants::MAC_F_N]) ? $decryptedArray[ComputopConstants::MAC_F_N] : null);
        $header->setXId(isset($decryptedArray[ComputopConstants::XID_F_N]) ? $decryptedArray[ComputopConstants::XID_F_N] : null);

        return $header;
    }

    /**
     * @inheritdoc
     */
    public function getResponseDecryptedArray($decryptedString)
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
     * @inheritdoc
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     */
    public function checkEncryptedResponse($responseArray)
    {
        $keys = [
            ComputopConstants::DATA_F_N,
            ComputopConstants::LENGTH_F_N,
        ];

        if (!$this->checkArrayKeysExists($keys, $responseArray)) {
            throw  new ComputopConverterException('Response does not have expected values. Please check Computop documentation.');
        }
    }

    /**
     * @inheritdoc
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     */
    public function checkMacResponse($responseMac, $neededMac, $method)
    {
        if ($this->config->isMacRequired($method) && $responseMac !== $neededMac) {
            throw  new ComputopConverterException('MAC is incorrect');
        }
    }

    /**
     * @param array $decryptedArray
     *
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     *
     * @return void
     */
    protected function checkDecryptedResponse($decryptedArray)
    {
        $keys = [
            ComputopConstants::MID_F_N,
            ComputopConstants::TRANS_ID_F_N,
            ComputopConstants::PAY_ID_F_N,
        ];

        if (!$this->checkArrayKeysExists($keys, $decryptedArray)) {
            throw  new ComputopConverterException('Response does not have expected values. Please check Computop documentation.');
        }
    }

    /**
     * @param array $keys
     * @param array $arraySearch
     *
     * @return bool
     */
    protected function checkArrayKeysExists($keys, $arraySearch)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $arraySearch)) {
                return false;
            }
        }

        return true;
    }

}
