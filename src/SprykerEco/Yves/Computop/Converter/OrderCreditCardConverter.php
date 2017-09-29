<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;

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
        $responseTransfer->setPCNr($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::PCN_R));
        $responseTransfer->setCCBrand($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::CC_BRAND));
        $responseTransfer->setCCExpiry($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::CC_EXPIRY));
        $responseTransfer->setMaskedPan($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::MASKED_PAN));
        $responseTransfer->setCavv($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::CAVV));
        $responseTransfer->setEci($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::ECI));
        $responseTransfer->setType($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::TYPE));
        $responseTransfer->setPlain($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::PLAIN));
        $responseTransfer->setCustom($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::CUSTOM));

        return $responseTransfer;
    }

}
