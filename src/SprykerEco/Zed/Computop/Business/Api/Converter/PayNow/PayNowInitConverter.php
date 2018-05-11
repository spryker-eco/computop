<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter\PayNow;

use Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer;
use SprykerEco\Zed\Computop\Business\Api\Converter\AbstractConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface;

class PayNowInitConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopPayNowInitResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopResponseTransfer = new ComputopPayNowInitResponseTransfer();
        $computopResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, $this->config->getReverseMethodName())
        );
        $computopResponseTransfer->fromArray($decryptedArray, true);

        return $computopResponseTransfer;
    }
}
