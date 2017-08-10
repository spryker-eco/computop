<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer;

class InquireConverter extends AbstractConverter implements ConverterInterface
{

    const EMPTY_AMOUNT = '0';

    /**
     * @param array $decryptedArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer
     */
    protected function getResponseTransfer($decryptedArray)
    {
        $computopCreditCardResponseTransfer = new ComputopCreditCardInquireResponseTransfer();

        $computopCreditCardResponseTransfer->fromArray($decryptedArray, true);
        $computopCreditCardResponseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray)
        );

        $computopCreditCardResponseTransfer->setIsAuthLast($this->isAuthLast($computopCreditCardResponseTransfer));
        $computopCreditCardResponseTransfer->setIsCapLast($this->isCapLast($computopCreditCardResponseTransfer));
        $computopCreditCardResponseTransfer->setIsCredLast($this->isCredLast($computopCreditCardResponseTransfer));

        return $computopCreditCardResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer $computopCreditCardResponseTransfer
     *
     * @return bool
     */
    protected function isAuthLast($computopCreditCardResponseTransfer)
    {
        return $computopCreditCardResponseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $computopCreditCardResponseTransfer->getAmountCap() === self::EMPTY_AMOUNT &&
            $computopCreditCardResponseTransfer->getAmountCred() === self::EMPTY_AMOUNT;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer $computopCreditCardResponseTransfer
     *
     * @return bool
     */
    protected function isCapLast($computopCreditCardResponseTransfer)
    {
        return $computopCreditCardResponseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $computopCreditCardResponseTransfer->getAmountCap() !== self::EMPTY_AMOUNT &&
            $computopCreditCardResponseTransfer->getAmountCred() === self::EMPTY_AMOUNT;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardInquireResponseTransfer $computopCreditCardResponseTransfer
     *
     * @return bool
     */
    protected function isCredLast($computopCreditCardResponseTransfer)
    {
        return $computopCreditCardResponseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $computopCreditCardResponseTransfer->getAmountCap() !== self::EMPTY_AMOUNT &&
            $computopCreditCardResponseTransfer->getAmountCred() !== self::EMPTY_AMOUNT;
    }

}
