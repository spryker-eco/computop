<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\AuthorizeCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\CaptureCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\InquireCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\RefundCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard\ReverseCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\DirectDebit\CaptureDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\DirectDebit\InquireDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\DirectDebit\RefundDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\DirectDebit\ReverseDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\AuthorizePayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\CapturePayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\InquirePayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\RefundPayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\PayPal\ReversePayPalMapper;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\Sofort\RefundSofortMapper;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer getQueryContainer()
 */
class ComputopBusinessMapperFactory extends ComputopBusinessFactory
{

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createAuthorizeCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new AuthorizeCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createReverseCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new ReverseCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createInquireCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new InquireCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createCaptureCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new CaptureCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createAuthorizePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new AuthorizePayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createReversePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new ReversePayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createInquirePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new InquirePayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createCapturePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new CapturePayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundPayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundPayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createReverseDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new ReverseDirectDebitMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createInquireDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new InquireDirectDebitMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createCaptureDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new CaptureDirectDebitMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundDirectDebitMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface
     */
    public function createRefundSofortMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundSofortMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

}
