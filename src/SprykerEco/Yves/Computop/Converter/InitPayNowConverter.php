<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;

class InitPayNowConverter extends AbstractInitConverter
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
        $responseTransfer = new ComputopPayNowInitResponseTransfer();
        $responseTransfer->fromArray($responseParamsArray, true);
        $responseTransfer->setHeader($header);
        //optional fields
        $responseTransfer->setPcNr($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::PC_NR));
        $responseTransfer->setCreditCardBrand($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::CREDIT_CARD_BRAND));
        $responseTransfer->setCreditCardExpiry($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::CREDIT_CARD_EXPIRY));
        $responseTransfer->setMaskedPan($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::MASKED_PAN));
        $responseTransfer->setCavv($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::CAVV));
        $responseTransfer->setEci($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::ECI));
        $responseTransfer->setType($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::TYPE));
        $responseTransfer->setPlain($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::PLAIN));
        $responseTransfer->setCustom($this->computopApiService->getResponseValue($responseParamsArray, ComputopApiConfig::CUSTOM));

        return $responseTransfer;
    }
}
