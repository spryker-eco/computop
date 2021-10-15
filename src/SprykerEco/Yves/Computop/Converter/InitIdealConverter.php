<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopIdealInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitIdealConverter extends AbstractInitConverter
{
    /**
     * @param array $responseParamsArray
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createResponseTransfer(
        array $responseParamsArray,
        ComputopApiResponseHeaderTransfer $header
    ): TransferInterface {
        $responseTransfer = new ComputopIdealInitResponseTransfer();
        $responseTransfer->fromArray($responseParamsArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setRefNr($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::REF_NR));
        $responseTransfer->setAccountBank($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ACCOUNT_BANK));
        $responseTransfer->setAccountOwner($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ACCOUNT_OWNER));
        $responseTransfer->setBankAccountBic($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BANK_ACCOUNT_BIC));
        $responseTransfer->setBankAccountIban($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BANK_ACCOUNT_IBAN));
        $responseTransfer->setPaymentPurpose($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::PAYMENT_PURPOSE));
        $responseTransfer->setPaymentGuarantee($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::PAYMENT_GUARANTEE));
        $responseTransfer->setErrorText($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ERROR_TEXT));
        $responseTransfer->setTransactionID($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::TRANSACTION_ID));
        $responseTransfer->setPlain($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::PLAIN));
        $responseTransfer->setCustom($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::CUSTOM));

        return $responseTransfer;
    }
}
