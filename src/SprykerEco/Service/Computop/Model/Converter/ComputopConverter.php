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
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class ComputopConverter extends AbstractComputop implements ComputopConverterInterface
{
    /**
     * @param array $decryptedArray
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function extractHeader(array $decryptedArray, $method)
    {
        $decryptedArray = $this->formatResponseArray($decryptedArray);
        $this->checkDecryptedResponse($decryptedArray);

        $header = new ComputopResponseHeaderTransfer();
        $header->fromArray($decryptedArray, true);
        $header->setMId($this->getResponseValue($decryptedArray, ComputopApiConfig::MERCHANT_ID_SHORT));
        $header->setTransId($this->getResponseValue($decryptedArray, ComputopApiConfig::TRANS_ID));
        $header->setPayId($this->getResponseValue($decryptedArray, ComputopApiConfig::PAY_ID));
        //optional
        $header->setMac($this->getResponseValue($decryptedArray, ComputopApiConfig::MAC));
        $header->setXId($this->getResponseValue($decryptedArray, ComputopApiConfig::X_ID));

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
            ComputopApiConfig::DATA,
            ComputopApiConfig::LENGTH,
        ];

        if (!$this->existArrayKeys($keys, $responseArray)) {
            throw new ComputopConverterException('Response does not have expected values. Please check Computop documentation.');
        }
    }

    /**
     * @param string $responseMac
     * @param string $expectedMac
     * @param string $method
     *
     * @throws \SprykerEco\Service\Computop\Exception\ComputopConverterException
     *
     * @return void
     */
    public function checkMacResponse($responseMac, $expectedMac, $method)
    {
        if ($this->config->isMacRequired($method) && $responseMac !== $expectedMac) {
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
    protected function checkDecryptedResponse(array $decryptedArray)
    {
        $keys = [
            ComputopApiConfig::MERCHANT_ID_SHORT,
            ComputopApiConfig::TRANS_ID,
            ComputopApiConfig::PAY_ID,
        ];

        if (!$this->existArrayKeys($keys, $decryptedArray)) {
            throw new ComputopConverterException('Response does not have expected values. Please check Computop documentation.');
        }
    }

    /**
     * @param array $keys
     * @param array $arraySearch
     *
     * @return bool
     */
    protected function existArrayKeys(array $keys, array $arraySearch)
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
        return mb_strtolower($key);
    }
}
