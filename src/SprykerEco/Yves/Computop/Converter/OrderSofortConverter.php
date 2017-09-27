<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderSofortConverter extends AbstractOrderConverter
{

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopSofortOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopSofortOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        $responseTransfer->setAccountBank($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ACCOUNT_BANK_F_N));
        $responseTransfer->setAccountOwner($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ACCOUNT_OWNER_F_N));
        $responseTransfer->setBankAccountBic($this->computopService->getResponseValue($decryptedArray, ComputopConstants::B_I_C_F_N));
        $responseTransfer->setBankAccountIban($this->computopService->getResponseValue($decryptedArray, ComputopConstants::I_B_A_N_F_N));
        //optional fields
        $responseTransfer->setFirstName($this->computopService->getResponseValue($decryptedArray, ComputopConstants::FIRST_NAME_F_N));
        $responseTransfer->setLastName($this->computopService->getResponseValue($decryptedArray, ComputopConstants::LAST_NAME_F_N));
        $responseTransfer->setAddressStreet($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ADDRESS_STREET_F_N));
        $responseTransfer->setAddressCity($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ADDRESS_CITY_F_N));
        $responseTransfer->setAddressZip($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ADDRESS_ZIP_F_N));
        $responseTransfer->setBirthday($this->computopService->getResponseValue($decryptedArray, ComputopConstants::BIRTHDAY_F_N));
        $responseTransfer->setAge($this->computopService->getResponseValue($decryptedArray, ComputopConstants::AGE_F_N));

        return $responseTransfer;
    }

}
