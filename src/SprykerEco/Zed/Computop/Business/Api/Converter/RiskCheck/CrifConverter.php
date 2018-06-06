<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter\RiskCheck;

use Generated\Shared\Transfer\ComputopCrifResponseTransfer;
use SprykerEco\Zed\Computop\Business\Api\Converter\AbstractConverter;
use SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface;

class CrifConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCrifResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopResponseTransfer = new ComputopCrifResponseTransfer();
        $computopResponseTransfer->fromArray($decryptedArray, true);
        $computopResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, $this->config->getAuthorizeMethodName())
        );

        return $computopResponseTransfer;
    }
}
