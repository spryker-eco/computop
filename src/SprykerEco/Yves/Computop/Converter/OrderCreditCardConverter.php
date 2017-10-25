<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopCreditCardOrderResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopFieldName;

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
        $responseTransfer->setPcnr($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::PCN_R));
        $responseTransfer->setCCBrand($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::CC_BRAND));
        $responseTransfer->setCCExpiry($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::CC_EXPIRY));
        $responseTransfer->setMaskedPan($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::MASKED_PAN));
        $responseTransfer->setCavv($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::CAVV));
        $responseTransfer->setEci($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::ECI));
        $responseTransfer->setType($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::TYPE));
        $responseTransfer->setPlain($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::PLAIN));
        $responseTransfer->setCustom($this->computopService->getResponseValue($decryptedArray, ComputopFieldName::CUSTOM));

        return $responseTransfer;
    }
}
