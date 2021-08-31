<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopPayuCeeSingleInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
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
    protected function createResponseTransfer(array $decryptedArray, ComputopApiResponseHeaderTransfer $header): TransferInterface
    {
        $responseTransfer = new ComputopPayuCeeSingleInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($this->updateResponseHeader($header));

        $fieldMap = [
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

        $responseTransfer->fromArray($this->getApiResponseValues($decryptedArray, $fieldMap), true);

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
     * @param string[] $decryptedResponse
     * @param string[] $responseFieldsMap
     *
     * @return array
     */
    protected function getApiResponseValues(array $decryptedResponse, array $responseFieldsMap): array
    {
        $apiResponseValues = [];
        foreach ($responseFieldsMap as $transferKey => $responseKey) {
            $apiResponseValues[$transferKey] = $this->computopApiService->getResponseValue($decryptedResponse, $responseKey);
        }

        return $apiResponseValues;
    }
}
