<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitCreditCardConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createResponseTransfer(array $decryptedArray, ComputopResponseHeaderTransfer $header)
    {
        $responseTransfer = new ComputopCreditCardInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setPcNr($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::PC_NR));
        $responseTransfer->setCreditCardBrand($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::CREDIT_CARD_BRAND));
        $responseTransfer->setCreditCardExpiry($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::CREDIT_CARD_EXPIRY));
        $responseTransfer->setMaskedPan($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::MASKED_PAN));
        $responseTransfer->setCavv($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::CAVV));
        $responseTransfer->setEci($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::ECI));
        $responseTransfer->setType($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::TYPE));
        $responseTransfer->setPlain($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::PLAIN));
        $responseTransfer->setCustom($this->computopService->getResponseValue($decryptedArray, ComputopApiConfig::CUSTOM));

        return $responseTransfer;
    }
}
