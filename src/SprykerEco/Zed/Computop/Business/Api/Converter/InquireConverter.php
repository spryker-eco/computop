<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use Generated\Shared\Transfer\ComputopInquireResponseTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\Computop\ComputopFieldNameConstants;

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
        $responseTransfer = new ComputopInquireResponseTransfer();
        $responseTransfer->fromArray($decryptedArray, true);
        $responseTransfer->setHeader(
            $this->computopService->extractHeader($decryptedArray, ComputopConstants::INQUIRE_METHOD)
        );
        $responseTransfer->setAmountAuth($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::AMOUNT_AUTH));
        $responseTransfer->setAmountCap($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::AMOUNT_CAP));
        $responseTransfer->setAmountCred($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::AMOUNT_CRED));
        $responseTransfer->setLastStatus($this->computopService->getResponseValue($decryptedArray, ComputopFieldNameConstants::LAST_STATUS));
        //set custom options
        $responseTransfer->setIsAuthLast($this->isAuthLast($responseTransfer));
        $responseTransfer->setIsCapLast($this->isCapLast($responseTransfer));
        $responseTransfer->setIsCredLast($this->isCredLast($responseTransfer));

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopInquireResponseTransfer $responseTransfer
     *
     * @return bool
     */
    protected function isAuthLast($responseTransfer)
    {
        return $responseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $responseTransfer->getAmountCap() === self::EMPTY_AMOUNT &&
            $responseTransfer->getAmountCred() === self::EMPTY_AMOUNT;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopInquireResponseTransfer $responseTransfer
     *
     * @return bool
     */
    protected function isCapLast($responseTransfer)
    {
        return $responseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $responseTransfer->getAmountCap() !== self::EMPTY_AMOUNT &&
            $responseTransfer->getAmountCred() === self::EMPTY_AMOUNT;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopInquireResponseTransfer $responseTransfer
     *
     * @return bool
     */
    protected function isCredLast($responseTransfer)
    {
        return $responseTransfer->getAmountAuth() !== self::EMPTY_AMOUNT &&
            $responseTransfer->getAmountCap() !== self::EMPTY_AMOUNT &&
            $responseTransfer->getAmountCred() !== self::EMPTY_AMOUNT;
    }

}
