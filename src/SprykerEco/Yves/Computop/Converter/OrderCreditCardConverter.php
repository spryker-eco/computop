<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class OrderCreditCardConverter extends AbstractOrderConverter
{

    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer
     */
    public function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopCreditCardOrderResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setPCNr($this->computopService->getResponseValue($decryptedArray, ComputopConstants::PCN_R_F_N));
        $responseTransfer->setCCBrand($this->computopService->getResponseValue($decryptedArray, ComputopConstants::CC_BRAND_F_N));
        $responseTransfer->setCCExpiry($this->computopService->getResponseValue($decryptedArray, ComputopConstants::CC_EXPIRY_F_N));
        $responseTransfer->setMaskedPan($this->computopService->getResponseValue($decryptedArray, ComputopConstants::MASKED_PAN_F_N));
        $responseTransfer->setCavv($this->computopService->getResponseValue($decryptedArray, ComputopConstants::CAVV_F_N));
        $responseTransfer->setEci($this->computopService->getResponseValue($decryptedArray, ComputopConstants::ECI_F_N));
        $responseTransfer->setType($this->computopService->getResponseValue($decryptedArray, ComputopConstants::TYPE_F_N));
        $responseTransfer->setPlain($this->computopService->getResponseValue($decryptedArray, ComputopConstants::PLAIN_F_N));
        $responseTransfer->setCustom($this->computopService->getResponseValue($decryptedArray, ComputopConstants::CUSTOM_F_N));

        return $responseTransfer;
    }

}
