<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopPayPalOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderPayPalConverter extends AbstractOrderConverter
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
        $responseTransfer->setEmail($this->computopService->getResponseValue($decryptedArray, ComputopConstants::EMAIL_F_N));
        $responseTransfer->setFirstName($this->computopService->getResponseValue($decryptedArray, ComputopConstants::FIRST_NAME_F_N));
        $responseTransfer->setLastName($this->computopService->getResponseValue($decryptedArray, ComputopConstants::LAST_NAME_F_N));
        //optional fields
        $responseTransfer->setTransactionId($this->computopService->getResponseValue($decryptedArray, ComputopConstants::TRANSACTION_ID_F_N));
        $responseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopConstants::REF_NR_F_N));
        $responseTransfer->setInfoText($this->computopService->getResponseValue($decryptedArray, ComputopConstants::INFO_TEXT_F_N));
        $responseTransfer->setBillingAgreementId($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BILLING_AGREEMENT_ID_F_N));
        $responseTransfer->setPhone($this->computopService->getResponseValue($decryptedArray, ComputopConstants::PHONE_F_N));
        $responseTransfer->setAddressStreet($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ADDRESS_STREET_F_N));
        $responseTransfer->setAddressStreet2($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ADDRESS_STREET2_F_N));
        $responseTransfer->setAddressCity($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ADDRESS_CITY_F_N));
        $responseTransfer->setAddressState($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ADDRESS_STATE_F_N));
        $responseTransfer->setAddressZip($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ADDRESS_ZIP_F_N));
        $responseTransfer->setBillingAddressCity($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BILLING_AGREEMENT_ID_F_N));
        $responseTransfer->setBillingAddressCountryCode($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BILLING_ADDRESS_COUNTRY_CODE_F_N));
        $responseTransfer->setBillingAddressState($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BILLING_ADDRESS_STATE_F_N));
        $responseTransfer->setBillingAddressStreet($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BILLING_ADDRESS_STREET_F_N));
        $responseTransfer->setBillingAddressStreet2($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BILLING_ADDRESS_STREET2_F_N));
        $responseTransfer->setBillingAddressZip($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BILLING_ADDRESS_ZIP_F_N));
        $responseTransfer->setBillingName($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BILLING_NAME_F_N));
        $responseTransfer->setPayerId($this->computopService->getResponseValue($decryptedArray, ComputopConstants::PAYER_ID_F_N));
        $responseTransfer->setIsFinancing($this->computopService->getResponseValue($decryptedArray, ComputopConstants::IS_FINANCING_F_N));
        $responseTransfer->setFinancingFeeAmount($this->computopService->getResponseValue($decryptedArray, ComputopConstants::FINANCING_FEE_AMOUNT_F_N));
        $responseTransfer->setFinancingMonthlyPayment($this->computopService->getResponseValue($decryptedArray, ComputopConstants::FINANCING_MONTHLY_PAYMENT_F_N));
        $responseTransfer->setFinancingTerm($this->computopService->getResponseValue($decryptedArray, ComputopConstants::FINANCING_TERM_F_N));
        $responseTransfer->setFinancingTotalCost($this->computopService->getResponseValue($decryptedArray, ComputopConstants::FINANCING_TOTAL_COST_F_N));
        $responseTransfer->setGrossAmount($this->computopService->getResponseValue($decryptedArray, ComputopConstants::GROSS_AMOUNT_F_N));
        $responseTransfer->setFeeAmount($this->computopService->getResponseValue($decryptedArray, ComputopConstants::FEE_AMOUNT_F_N));
        $responseTransfer->setSettleAmount($this->computopService->getResponseValue($decryptedArray, ComputopConstants::SETTLE_AMOUNT_F_N));
        $responseTransfer->setTaxAmount($this->computopService->getResponseValue($decryptedArray, ComputopConstants::TAX_AMOUNT_F_N));
        $responseTransfer->setExchangeRate($this->computopService->getResponseValue($decryptedArray, ComputopConstants::EXCHANGE_RATE_F_N));
        $responseTransfer->setMcFee($this->computopService->getResponseValue($decryptedArray, ComputopConstants::MC_FEE_F_N));
        $responseTransfer->setMcGross($this->computopService->getResponseValue($decryptedArray, ComputopConstants::MC_GROSS_F_N));

        return $responseTransfer;
    }

}
