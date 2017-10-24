<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;

interface ComputopBusinessMapperFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createAuthorizeCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createReverseCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createInquireCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createCaptureCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createAuthorizePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createReversePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createInquirePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createCapturePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundPayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createReverseDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createInquireDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createCaptureDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundSofortMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createReversePaydirektMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createInquirePaydirektMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createCapturePaydirektMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundPaydirektMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment);
}
