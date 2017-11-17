<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter\EasyCredit;

use Generated\Shared\Transfer\ComputopEasyCreditStatusResponseTransfer;
use SprykerEco\Zed\Computop\Business\Api\Converter\AbstractConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface;

class EasyCreditStatusConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopEasyCreditStatusResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopResponseTransfer = new ComputopEasyCreditStatusResponseTransfer();
        $computopResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, $this->config->getReverseMethodName())
        );
        //optional fields
        //todo: extract JSON format

        return $computopResponseTransfer;
    }
}
