<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopPayPalOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPayPalConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopPayPalOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setEmail($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::EMAIL));
        $responseTransfer->setFirstName($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::FIRST_NAME));
        $responseTransfer->setLastName($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::LAST_NAME));
        //optional fields
        $responseTransfer->setTransactionId($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::TRANSACTION_ID));
        $responseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::REF_NR));
        $responseTransfer->setInfoText($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::INFO_TEXT));
        $responseTransfer->setBillingAgreementId($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_AGREEMENT_ID));
        $responseTransfer->setPhone($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::PHONE));
        $responseTransfer->setAddressStreet($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_STREET));
        $responseTransfer->setAddressStreet2($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_STREET2));
        $responseTransfer->setAddressCity($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_CITY));
        $responseTransfer->setAddressState($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_STATE));
        $responseTransfer->setAddressZip($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_ZIP));
        $responseTransfer->setBillingAddressCity($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_AGREEMENT_ID));
        $responseTransfer->setBillingAddressCountryCode($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_COUNTRY_CODE));
        $responseTransfer->setBillingAddressState($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_STATE));
        $responseTransfer->setBillingAddressStreet($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_STREET));
        $responseTransfer->setBillingAddressStreet2($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_STREET2));
        $responseTransfer->setBillingAddressZip($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_ZIP));
        $responseTransfer->setBillingName($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_NAME));
        $responseTransfer->setPayerId($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::PAYER_ID));
        $responseTransfer->setIsFinancing($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::IS_FINANCING));
        $responseTransfer->setFinancingFeeAmount($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::FINANCING_FEE_AMOUNT));
        $responseTransfer->setFinancingMonthlyPayment($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::FINANCING_MONTHLY_PAYMENT));
        $responseTransfer->setFinancingTerm($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::FINANCING_TERM));
        $responseTransfer->setFinancingTotalCost($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::FINANCING_TOTAL_COST));
        $responseTransfer->setGrossAmount($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::GROSS_AMOUNT));
        $responseTransfer->setFeeAmount($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::FEE_AMOUNT));
        $responseTransfer->setSettleAmount($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::SETTLE_AMOUNT));
        $responseTransfer->setTaxAmount($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::TAX_AMOUNT));
        $responseTransfer->setExchangeRate($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::EXCHANGE_RATE));
        $responseTransfer->setMcFee($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::MC_FEE));
        $responseTransfer->setMcGross($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::MC_GROSS));

        return $responseTransfer;
    }
}
