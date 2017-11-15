<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopIdealInitResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitIdealConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopIdealInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::REF_NR));
        $responseTransfer->setAccountBank($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_BANK));
        $responseTransfer->setAccountOwner($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ACCOUNT_OWNER));
        $responseTransfer->setBankAccountBic($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_BIC));
        $responseTransfer->setBankAccountIban($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BANK_ACCOUNT_IBAN));
        $responseTransfer->setPaymentPurpose($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::PAYMENT_PURPOSE));
        $responseTransfer->setPaymentGuarantee($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::PAYMENT_GUARANTEE));
        $responseTransfer->setErrorText($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ERROR_TEXT));
        $responseTransfer->setTransactionID($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::TRANSACTION_ID));
        $responseTransfer->setPlain($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::PLAIN));
        $responseTransfer->setCustom($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::CUSTOM));

        return $responseTransfer;
    }
}
