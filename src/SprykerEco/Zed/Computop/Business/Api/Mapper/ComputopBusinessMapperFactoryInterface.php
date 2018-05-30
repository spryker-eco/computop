<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper;

interface ComputopBusinessMapperFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createAuthorizeCreditCardMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createReverseCreditCardMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createInquireCreditCardMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createCaptureCreditCardMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createRefundCreditCardMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createAuthorizePayNowMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createReversePayNowMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createInquirePayNowMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createCapturePayNowMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createRefundPayNowMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createAuthorizePayPalMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createReversePayPalMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createInquirePayPalMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createCapturePayPalMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createRefundPayPalMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createReverseDirectDebitMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createInquireDirectDebitMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createCaptureDirectDebitMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createRefundDirectDebitMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createRefundSofortMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createReversePaydirektMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createInquirePaydirektMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createCapturePaydirektMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createRefundPaydirektMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createAuthorizeEasyCreditMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createCaptureEasyCreditMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createRefundEasyCreditMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createCaptureIdealMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace\ApiPostPlaceMapperInterface
     */
    public function createRefundIdealMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\ApiPrePlaceMapperInterface
     */
    public function createStatusEasyCreditMapper();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Mapper\PrePlace\PayNow\InitPayNowMapper
     */
    public function createInitPayNowMapper();
}
