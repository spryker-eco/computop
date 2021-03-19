<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPayuCeeSingleConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer
     */
    protected function createResponseTransfer(array $decryptedArray, ComputopApiResponseHeaderTransfer $header): ComputopPayuCeeSingleInitResponseTransfer
    {
        $responseTransfer = new ComputopPayuCeeSingleInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($this->updateResponseHeader($header));

        $arrayMap = [
            'refNr' => ComputopApiConfig::REF_NR,
            'codeExt' => ComputopApiConfig::CODE_EXT,
            'errorText' => ComputopApiConfig::ERROR_TEXT,
            'userData' => ComputopApiConfig::USER_DATA,
            'plain' => ComputopApiConfig::PLAIN,
            'custom' => ComputopApiConfig::CUSTOM,
            'customerTransactionId' => ComputopApiConfig::TRANSACTION_ID,
            'amountAuth' => ComputopApiConfig::AMOUNT_AUTH,
            'amountCap' => ComputopApiConfig::AMOUNT_CAP,
            'amountCred' => ComputopApiConfig::AMOUNT_CRED,
        ];

        $responseTransfer->fromArray($this->getApiResponseValues($decryptedArray, $arrayMap), true);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer
     */
    protected function updateResponseHeader(ComputopApiResponseHeaderTransfer $header): ComputopApiResponseHeaderTransfer
    {
        if ($header->getStatus() === ComputopConfig::AUTHORIZE_REQUEST_STATUS) {
            $header->setIsSuccess(true);
        }

        return $header;
    }

    /**
     * @param array $decryptedArray
     * @param string[] $keysMap
     *
     * @return string[]
     */
    private function getApiResponseValues(array $decryptedArray, array $keysMap): array
    {
        $out = [];
        foreach ($keysMap as $key => $value) {
            $out[$key] = $this->computopApiService->getResponseValue($decryptedArray, $value);
        }

        return $out;
    }
}
