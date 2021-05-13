<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopPayPalExpressInitResponseTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPayPalExpressConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createResponseTransfer(array $decryptedArray, ComputopApiResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopPayPalExpressInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setEmail($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::EMAIL));
        $responseTransfer->setFirstName($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::FIRST_NAME));
        $responseTransfer->setLastName($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::LAST_NAME));
        //optional fields
        $responseTransfer->setTransactionId($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::TRANSACTION_ID));
        $responseTransfer->setRefNr($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::REF_NR));
        $responseTransfer->setInfoText($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::INFO_TEXT));
        $responseTransfer->setBillingAgreementId($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_AGREEMENT_ID));
        $responseTransfer->setPhone($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::PHONE));
        $responseTransfer->setAddressCountryCode($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_COUNTRY_CODE));
        $responseTransfer->setAddressStreet($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_STREET));
        $responseTransfer->setAddressStreet2($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_STREET2));
        $responseTransfer->setAddressCity($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_CITY));
        $responseTransfer->setAddressState($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_STATE));
        $responseTransfer->setAddressZip($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ADDRESS_ZIP));
        $responseTransfer->setBillingAddressCity($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_CITY));
        $responseTransfer->setBillingAddressCountryCode($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_COUNTRY_CODE));
        $responseTransfer->setBillingAddressState($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_STATE));
        $responseTransfer->setBillingAddressStreet($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_STREET));
        $responseTransfer->setBillingAddressStreet2($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_STREET2));
        $responseTransfer->setBillingAddressZip($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_ADDRESS_ZIP));
        $responseTransfer->setBillingName($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::BILLING_NAME));
        $responseTransfer->setPayerId($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::PAYER_ID));
        $responseTransfer->setIsFinancing($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::IS_FINANCING));
        $responseTransfer->setFinancingFeeAmount($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::FINANCING_FEE_AMOUNT));
        $responseTransfer->setFinancingMonthlyPayment($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::FINANCING_MONTHLY_PAYMENT));
        $responseTransfer->setFinancingTerm($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::FINANCING_TERM));
        $responseTransfer->setFinancingTotalCost($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::FINANCING_TOTAL_COST));
        $responseTransfer->setGrossAmount($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::GROSS_AMOUNT));
        $responseTransfer->setFeeAmount($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::FEE_AMOUNT));
        $responseTransfer->setSettleAmount($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::SETTLE_AMOUNT));
        $responseTransfer->setTaxAmount($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::TAX_AMOUNT));
        $responseTransfer->setExchangeRate($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::EXCHANGE_RATE));
        $responseTransfer->setMcFee($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::MC_FEE));
        $responseTransfer->setMcGross($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::MC_GROSS));

        return $responseTransfer;
    }
}
