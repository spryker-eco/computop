<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopPayPalOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;

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
        $responseTransfer->setEmail($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::EMAIL));
        $responseTransfer->setFirstName($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::FIRST_NAME));
        $responseTransfer->setLastName($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::LAST_NAME));
        //optional fields
        $responseTransfer->setTransactionId($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::TRANSACTION_ID));
        $responseTransfer->setRefNr($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::REF_NR));
        $responseTransfer->setInfoText($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::INFO_TEXT));
        $responseTransfer->setBillingAgreementId($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::BILLING_AGREEMENT_ID));
        $responseTransfer->setPhone($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::PHONE));
        $responseTransfer->setAddressStreet($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::ADDRESS_STREET));
        $responseTransfer->setAddressStreet2($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::ADDRESS_STREET2));
        $responseTransfer->setAddressCity($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::ADDRESS_CITY));
        $responseTransfer->setAddressState($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::ADDRESS_STATE));
        $responseTransfer->setAddressZip($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::ADDRESS_ZIP));
        $responseTransfer->setBillingAddressCity($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::BILLING_AGREEMENT_ID));
        $responseTransfer->setBillingAddressCountryCode($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::BILLING_ADDRESS_COUNTRY_CODE));
        $responseTransfer->setBillingAddressState($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::BILLING_ADDRESS_STATE));
        $responseTransfer->setBillingAddressStreet($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::BILLING_ADDRESS_STREET));
        $responseTransfer->setBillingAddressStreet2($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::BILLING_ADDRESS_STREET2));
        $responseTransfer->setBillingAddressZip($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::BILLING_ADDRESS_ZIP));
        $responseTransfer->setBillingName($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::BILLING_NAME));
        $responseTransfer->setPayerId($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::PAYER_ID));
        $responseTransfer->setIsFinancing($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::IS_FINANCING));
        $responseTransfer->setFinancingFeeAmount($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::FINANCING_FEE_AMOUNT));
        $responseTransfer->setFinancingMonthlyPayment($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::FINANCING_MONTHLY_PAYMENT));
        $responseTransfer->setFinancingTerm($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::FINANCING_TERM));
        $responseTransfer->setFinancingTotalCost($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::FINANCING_TOTAL_COST));
        $responseTransfer->setGrossAmount($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::GROSS_AMOUNT));
        $responseTransfer->setFeeAmount($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::FEE_AMOUNT));
        $responseTransfer->setSettleAmount($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::SETTLE_AMOUNT));
        $responseTransfer->setTaxAmount($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::TAX_AMOUNT));
        $responseTransfer->setExchangeRate($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::EXCHANGE_RATE));
        $responseTransfer->setMcFee($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::MC_FEE));
        $responseTransfer->setMcGross($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::MC_GROSS));

        return $responseTransfer;
    }

}
