<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api;

interface ComputopBusinessApiFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createAuthorizationPaymentRequest();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createInquirePaymentRequest();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createReversePaymentRequest();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createCapturePaymentRequest();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createRefundPaymentRequest();
    
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PrePlace\PrePlaceRequestInterface
     */
    public function createEasyCreditStatusRequest();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\PostPlace\PostPlaceRequestInterface
     */
    public function createEasyCreditAuthorizeRequest();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Request\Init\PayNowInitRequest
     */
    public function createPayNowInitRequest();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createAuthorizeConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createReverseConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createInquireConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createCaptureConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createRefundConverter();
    
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createEasyCreditConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\PayNow\PayNowInitConverter
     */
    public function createPayNowInitConverter();
}
