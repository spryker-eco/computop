<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Service\Computop\Exception\ComputopConverterException;
use SprykerEco\Service\Computop\Model\AbstractComputop;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;

class Computop extends AbstractComputop implements ComputopInterface
{

    /**
     * @param array $decryptedArray
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader(array $decryptedArray, $method)
    {
        $this->checkDecryptedResponse($decryptedArray);

        $header = new ComputopResponseHeaderTransfer();
        $header->fromArray($decryptedArray, true);
        $header->setMId($this->getResponseValue($decryptedArray, ComputopFieldNameConstants::MID));
        $header->setTransId($this->getResponseValue($decryptedArray, ComputopFieldNameConstants::TRANS_ID));
        $header->setPayId($this->getResponseValue($decryptedArray, ComputopFieldNameConstants::PAY_ID));
        //optional
        $header->setMac($this->getResponseValue($decryptedArray, ComputopFieldNameConstants::MAC));
        $header->setXId($this->getResponseValue($decryptedArray, ComputopFieldNameConstants::XID));

        $header->setIsSuccess($header->getStatus() === ComputopConfig::SUCCESS_STATUS);
        $header->setMethod($method);

        return $header;
    }

    /**
     * @param array $responseArray
     * @param string $key
     *
     * @return null|string
     */
    public function getResponseValue(array $responseArray, $key)
    {
        if (isset($responseArray[$this->formatKey($key)])) {
            return $responseArray[$this->formatKey($key)];
        }

        return null;
    }

    /**
     * @param string $decryptedString
     *
     * @return array
     */
    public function getResponseDecryptedArray($decryptedString)
    {
        $decryptedArray = [];
        $decryptedSubArray = explode(self::DATA_SEPARATOR, $decryptedString);
        foreach ($decryptedSubArray as $value) {
            $data = explode(self::DATA_SUB_SEPARATOR, $value);
            $decryptedArray[array_shift($data)] = array_shift($data);
        }

        return $this->formatResponseArray($decryptedArray);
    }

    /**
     * @param array $responseArray
     *
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     *
     * @return void
     */
    public function checkEncryptedResponse(array $responseArray)
    {
        $keys = [
            ComputopFieldNameConstants::DATA,
            ComputopFieldNameConstants::LENGTH,
        ];

        if (!$this->checkArrayKeysExists($keys, $responseArray)) {
            throw new ComputopConverterException('Response does not have expected values. Please check Computop documentation.');
        }
    }

    /**
     * @param string $responseMac
     * @param string $neededMac
     * @param string $method
     *
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     *
     * @return void
     */
    public function checkMacResponse($responseMac, $neededMac, $method)
    {
        if ($this->config->isMacRequired($method) && $responseMac !== $neededMac) {
            throw new ComputopConverterException('MAC is incorrect');
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
            ComputopFieldNameConstants::MID,
            ComputopFieldNameConstants::TRANS_ID,
            ComputopFieldNameConstants::PAY_ID,
        ];

        if (!$this->checkArrayKeysExists($keys, $decryptedArray)) {
            throw new ComputopConverterException('Response does not have expected values. Please check Computop documentation.');
        }
    }

    /**
     * @param array $keys
     * @param array $arraySearch
     *
     * @return bool
     */
    protected function checkArrayKeysExists(array $keys, array $arraySearch)
    {
        $arraySearch = $this->formatResponseArray($arraySearch);

        foreach ($keys as $key) {
            if (!array_key_exists($this->formatKey($key), $arraySearch)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Computop returns keys in different formats. F.e. "BIC", "bic".
     * Function returns keys in one unique format.
     *
     * @param array $decryptedArray
     *
     * @return array
     */
    protected function formatResponseArray(array $decryptedArray)
    {
        $formattedArray = [];

        foreach ($decryptedArray as $key => $value) {
            $formattedArray[$this->formatKey($key)] = $value;
        }

        return $formattedArray;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function formatKey($key)
    {
        return strtolower($key);
    }

}
