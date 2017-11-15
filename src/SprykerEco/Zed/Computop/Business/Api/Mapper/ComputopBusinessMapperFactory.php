<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper;

use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\AuthorizeCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\CaptureCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\InquireCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\RefundCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\CreditCard\ReverseCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\DirectDebit\CaptureDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\DirectDebit\InquireDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\DirectDebit\RefundDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\DirectDebit\ReverseDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\EasyCredit\CaptureEasyCreditMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\EasyCredit\RefundEasyCreditMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Ideal\CaptureIdealMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\Ideal\RefundIdealMapper;
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
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createAuthorizeCreditCardMapper()
    {
        return new AuthorizeCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createReverseCreditCardMapper()
    {
        return new ReverseCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createInquireCreditCardMapper()
    {
        return new InquireCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCaptureCreditCardMapper()
    {
        return new CaptureCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundCreditCardMapper()
    {
        return new RefundCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createAuthorizePayPalMapper()
    {
        return new AuthorizePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createReversePayPalMapper()
    {
        return new ReversePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createInquirePayPalMapper()
    {
        return new InquirePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCapturePayPalMapper()
    {
        return new CapturePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundPayPalMapper()
    {
        return new RefundPayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createReverseDirectDebitMapper()
    {
        return new ReverseDirectDebitMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createInquireDirectDebitMapper()
    {
        return new InquireDirectDebitMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCaptureDirectDebitMapper()
    {
        return new CaptureDirectDebitMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundDirectDebitMapper()
    {
        return new RefundDirectDebitMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundSofortMapper()
    {
        return new RefundSofortMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createReversePaydirektMapper()
    {
        return new ReversePaydirektMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createInquirePaydirektMapper()
    {
        return new InquirePaydirektMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCapturePaydirektMapper()
    {
        return new CapturePaydirektMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundPaydirektMapper()
    {
        return new RefundPaydirektMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCaptureEasyCreditMapper()
    {
        return new CaptureEasyCreditMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundEasyCreditMapper()
    {
        return new RefundEasyCreditMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createCaptureIdealMapper()
    {
        return new CaptureIdealMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\ApiMapperInterface
     */
    public function createRefundIdealMapper()
    {
        return new RefundIdealMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }
}
