<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopIdealOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;

class OrderIdealConverter extends AbstractOrderConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopIdealOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopIdealOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::REF_NR));
        $responseTransfer->setAccountBank($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::ACCOUNT_BANK));
        $responseTransfer->setAccountOwner($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::ACCOUNT_OWNER));
        $responseTransfer->setBankAccountBic($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::B_I_C));
        $responseTransfer->setBankAccountIban($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::I_B_A_N));
        $responseTransfer->setPaymentPurpose($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::PAYMENT_PURPOSE));
        $responseTransfer->setPaymentGuarantee($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::PAYMENT_GUARANTEE));
        $responseTransfer->setErrorText($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::ERROR_TEXT));
        $responseTransfer->setTransactionID($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::TRANSACTION_ID));
        $responseTransfer->setPlain($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::PLAIN));
        $responseTransfer->setCustom($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::CUSTOM));

        return $responseTransfer;
    }
}
