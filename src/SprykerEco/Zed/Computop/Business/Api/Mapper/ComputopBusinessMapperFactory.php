<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper;

use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\CreditCard\AuthorizeCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\CreditCard\CaptureCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\CreditCard\InquireCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\CreditCard\RefundCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\CreditCard\ReverseCreditCardMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\DirectDebit\CaptureDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\DirectDebit\InquireDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\DirectDebit\RefundDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\DirectDebit\ReverseDirectDebitMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\EasyCredit\AuthorizeEasyCreditMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\EasyCredit\CaptureEasyCreditMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\EasyCredit\RefundEasyCreditMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\Ideal\CaptureIdealMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\Ideal\RefundIdealMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\Paydirekt\CapturePaydirektMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\Paydirekt\InquirePaydirektMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\Paydirekt\RefundPaydirektMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\Paydirekt\ReversePaydirektMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\PayPal\AuthorizePayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\PayPal\CapturePayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\PayPal\InquirePayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\PayPal\RefundPayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\PayPal\ReversePayPalMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\Sofort\RefundSofortMapper;
use SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\EasyCredit\StatusEasyCreditMapper;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 */
class ComputopBusinessMapperFactory extends ComputopBusinessFactory implements ComputopBusinessMapperFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createAuthorizeCreditCardMapper()
    {
        return new AuthorizeCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createReverseCreditCardMapper()
    {
        return new ReverseCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createInquireCreditCardMapper()
    {
        return new InquireCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createCaptureCreditCardMapper()
    {
        return new CaptureCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createRefundCreditCardMapper()
    {
        return new RefundCreditCardMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createAuthorizePayPalMapper()
    {
        return new AuthorizePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createReversePayPalMapper()
    {
        return new ReversePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createInquirePayPalMapper()
    {
        return new InquirePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createCapturePayPalMapper()
    {
        return new CapturePayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createRefundPayPalMapper()
    {
        return new RefundPayPalMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createReverseDirectDebitMapper()
    {
        return new ReverseDirectDebitMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createInquireDirectDebitMapper()
    {
        return new InquireDirectDebitMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createCaptureDirectDebitMapper()
    {
        return new CaptureDirectDebitMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createRefundDirectDebitMapper()
    {
        return new RefundDirectDebitMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createRefundSofortMapper()
    {
        return new RefundSofortMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createReversePaydirektMapper()
    {
        return new ReversePaydirektMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createInquirePaydirektMapper()
    {
        return new InquirePaydirektMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createCapturePaydirektMapper()
    {
        return new CapturePaydirektMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createRefundPaydirektMapper()
    {
        return new RefundPaydirektMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createAuthorizeEasyCreditMapper()
    {
        return new AuthorizeEasyCreditMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createCaptureEasyCreditMapper()
    {
        return new CaptureEasyCreditMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createRefundEasyCreditMapper()
    {
        return new RefundEasyCreditMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createCaptureIdealMapper()
    {
        return new CaptureIdealMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface;
     */
    public function createRefundIdealMapper()
    {
        return new RefundIdealMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\ApiPrePlaceMapperInterface;
     */
    public function createStatusEasyCreditMapper()
    {
        return new StatusEasyCreditMapper($this->getComputopService(), $this->getConfig(), $this->getStore());
    }
}
