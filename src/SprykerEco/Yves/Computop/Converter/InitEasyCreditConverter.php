<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Converter;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopEasyCreditInitResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig;

class InitEasyCreditConverter extends AbstractInitConverter
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
        $responseTransfer = new ComputopEasyCreditInitResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader($this->updateResponseHeader($header));

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $header
     *
     * @return \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer
     */
    protected function updateResponseHeader(ComputopApiResponseHeaderTransfer $header): ComputopApiResponseHeaderTransfer
    {
        if ($header->getStatus() === ComputopConfig::AUTHORIZE_REQUEST_STATUS) {
            $header->setIsSuccess(true);
        }

        return $header;
    }
}
