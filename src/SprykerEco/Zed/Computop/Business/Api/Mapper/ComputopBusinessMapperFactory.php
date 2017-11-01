<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\AuthorizeCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\CaptureCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\InquireCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\RefundCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\ReverseCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\DirectDebit\CaptureDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\DirectDebit\InquireDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\DirectDebit\RefundDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\DirectDebit\ReverseDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Paydirekt\CapturePaydirektMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Paydirekt\InquirePaydirektMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Paydirekt\RefundPaydirektMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Paydirekt\ReversePaydirektMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PayPal\AuthorizePayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PayPal\CapturePayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PayPal\InquirePayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PayPal\RefundPayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PayPal\ReversePayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Sofort\RefundSofortMapper;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class ComputopBusinessMapperFactory extends ComputopBusinessFactory implements ComputopBusinessMapperFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createAuthorizeCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new AuthorizeCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createReverseCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new ReverseCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createInquireCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new InquireCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCaptureCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new CaptureCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundCreditCardMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundCreditCardMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createAuthorizePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new AuthorizePayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createReversePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new ReversePayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createInquirePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new InquirePayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCapturePayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new CapturePayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundPayPalMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundPayPalMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createReverseDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new ReverseDirectDebitMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createInquireDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new InquireDirectDebitMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCaptureDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new CaptureDirectDebitMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundDirectDebitMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundDirectDebitMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundSofortMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundSofortMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createReversePaydirektMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new ReversePaydirektMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createInquirePaydirektMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new InquirePaydirektMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCapturePaydirektMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new CapturePaydirektMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundPaydirektMapper(ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        return new RefundPaydirektMapper($this->getComputopService(), $this->getConfig(), $computopHeaderPayment);
    }
}
