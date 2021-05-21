<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopCreditCardInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitCreditCardConverter extends AbstractInitConverter
{
    /**
     * @param array $decryptedArray
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createResponseTransfer(
        array $decryptedArray,
        ComputopApiResponseHeaderTransfer $header
    ): TransferInterface {
        $responseTransfer = new ComputopCreditCardInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setPcNr($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::PC_NR));
        $responseTransfer->setCreditCardBrand($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::CREDIT_CARD_BRAND));
        $responseTransfer->setCreditCardExpiry($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::CREDIT_CARD_EXPIRY));
        $responseTransfer->setMaskedPan($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::MASKED_PAN));
        $responseTransfer->setCavv($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::CAVV));
        $responseTransfer->setEci($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::ECI));
        $responseTransfer->setType($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::TYPE));
        $responseTransfer->setPlain($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::PLAIN));
        $responseTransfer->setCustom($this->computopApiService->getResponseValue($decryptedArray, ComputopApiConfig::CUSTOM));

        return $responseTransfer;
    }
}
