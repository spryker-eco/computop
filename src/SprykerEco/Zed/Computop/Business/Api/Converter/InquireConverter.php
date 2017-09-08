<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopInquireResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;

class InquireConverter extends AbstractConverter implements ConverterInterface
{

    const EMPTY_AMOUNT = '0';

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopInquireResponseTransfer
     */
    protected function getResponseTransfer(array $decryptedArray)
    {
        $computopResponseTransfer = new ComputopInquireResponseTransfer();

        $computopResponseTransfer->fromArray($decryptedArray, true);
        $computopResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, ComputopConstants::INQUIRE_METHOD)
        );

        $computopResponseTransfer->setIsAuthLast($this->isAuthLast($computopResponseTransfer));
        $computopResponseTransfer->setIsCapLast($this->isCapLast($computopResponseTransfer));
        $computopResponseTransfer->setIsCredLast($this->isCredLast($computopResponseTransfer));

        return $computopResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopInquireResponseTransfer $computopResponseTransfer
     *
     * @return bool
     */
    protected function isAuthLast($computopResponseTransfer)
    {
        return $computopResponseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $computopResponseTransfer->getAmountCap() === self::EMPTY_AMOUNT &&
            $computopResponseTransfer->getAmountCred() === self::EMPTY_AMOUNT;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopInquireResponseTransfer $computopResponseTransfer
     *
     * @return bool
     */
    protected function isCapLast($computopResponseTransfer)
    {
        return $computopResponseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $computopResponseTransfer->getAmountCap() !== self::EMPTY_AMOUNT &&
            $computopResponseTransfer->getAmountCred() === self::EMPTY_AMOUNT;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopInquireResponseTransfer $computopResponseTransfer
     *
     * @return bool
     */
    protected function isCredLast($computopResponseTransfer)
    {
        return $computopResponseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $computopResponseTransfer->getAmountCap() !== self::EMPTY_AMOUNT &&
            $computopResponseTransfer->getAmountCred() !== self::EMPTY_AMOUNT;
    }

}
