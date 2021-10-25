<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopPayPalInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPayPalConverter extends AbstractInitConverter
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
        $responseTransfer = new ComputopPayPalInitResponseTransfer();
        $responseTransfer->fromArray($responseParamsArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setEmail($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::EMAIL));
        $responseTransfer->setFirstName($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::FIRST_NAME));
        $responseTransfer->setLastName($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::LAST_NAME));
        //optional fields
        $responseTransfer->setTransactionId($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::TRANSACTION_ID));
        $responseTransfer->setRefNr($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::REF_NR));
        $responseTransfer->setInfoText($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::INFO_TEXT));
        $responseTransfer->setBillingAgreementId($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BILLING_AGREEMENT_ID));
        $responseTransfer->setPhone($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::PHONE));
        $responseTransfer->setAddressStreet($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ADDRESS_STREET));
        $responseTransfer->setAddressStreet2($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ADDRESS_STREET2));
        $responseTransfer->setAddressCity($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ADDRESS_CITY));
        $responseTransfer->setAddressState($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ADDRESS_STATE));
        $responseTransfer->setAddressZip($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ADDRESS_ZIP));
        $responseTransfer->setBillingAddressCity($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BILLING_AGREEMENT_ID));
        $responseTransfer->setBillingAddressCountryCode($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BILLING_ADDRESS_COUNTRY_CODE));
        $responseTransfer->setBillingAddressState($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BILLING_ADDRESS_STATE));
        $responseTransfer->setBillingAddressStreet($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BILLING_ADDRESS_STREET));
        $responseTransfer->setBillingAddressStreet2($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BILLING_ADDRESS_STREET2));
        $responseTransfer->setBillingAddressZip($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BILLING_ADDRESS_ZIP));
        $responseTransfer->setBillingName($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::BILLING_NAME));
        $responseTransfer->setPayerId($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::PAYER_ID));
        $responseTransfer->setIsFinancing($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::IS_FINANCING));
        $responseTransfer->setFinancingFeeAmount($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::FINANCING_FEE_AMOUNT));
        $responseTransfer->setFinancingMonthlyPayment($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::FINANCING_MONTHLY_PAYMENT));
        $responseTransfer->setFinancingTerm($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::FINANCING_TERM));
        $responseTransfer->setFinancingTotalCost($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::FINANCING_TOTAL_COST));
        $responseTransfer->setGrossAmount($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::GROSS_AMOUNT));
        $responseTransfer->setFeeAmount($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::FEE_AMOUNT));
        $responseTransfer->setSettleAmount($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::SETTLE_AMOUNT));
        $responseTransfer->setTaxAmount($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::TAX_AMOUNT));
        $responseTransfer->setExchangeRate($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::EXCHANGE_RATE));
        $responseTransfer->setMcFee($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::MC_FEE));
        $responseTransfer->setMcGross($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::MC_GROSS));

        return $responseTransfer;
    }
}
