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
    const DECISION_INDEX = 'desicion';

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
        $computopResponseTransfer->fromArray($decryptedArray, true);

        //easy credit index mistake will be fixed in future
        if (isset($decryptedArray[static::DECISION_INDEX])) {
            $computopResponseTransfer->setDecision($decryptedArray[static::DECISION_INDEX]);
        }

        return $computopResponseTransfer;
    }
}
