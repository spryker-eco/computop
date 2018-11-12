<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Computop\ComputopConfig as SharedComputopConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Shared\ComputopApi\ComputopApiConstants;

class ComputopConfig extends AbstractBundleConfig
{
    const ETI_ID = 'Spryker – MV:%s';
    const OMS_STATUS_NEW = 'new';
    const OMS_STATUS_INITIALIZED = 'init';
    const OMS_STATUS_AUTHORIZED = 'authorized';
    const OMS_STATUS_AUTHORIZATION_FAILED = 'authorization failed';
    const OMS_STATUS_CAPTURED = 'captured';
    const OMS_STATUS_CAPTURING_FAILED = 'capture failed';
    const OMS_STATUS_CANCELLED = 'cancelled';
    const OMS_STATUS_REFUNDED = 'refunded';

    const AUTHORIZE_METHOD = 'AUTHORIZE';
    const CAPTURE_METHOD = 'CAPTURE';
    const REVERSE_METHOD = 'REVERSE';
    const INQUIRE_METHOD = 'INQUIRE';
    const REFUND_METHOD = 'REFUND';

    //Events
    const COMPUTOP_OMS_EVENT_CAPTURE = 'capture';
    const COMPUTOP_OMS_EVENT_AUTHORIZE = 'authorize';

    /**
     * Refund with shipment price
     */
    const COMPUTOP_REFUND_SHIPMENT_PRICE_ENABLED = true;

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->get(ComputopApiConstants::MERCHANT_ID);
    }

    /**
     * @return string
     */
    public function getBlowfishPass()
    {
        return $this->get(ComputopApiConstants::BLOWFISH_PASSWORD);
    }

    /**
     * @return string
     */
    public function getAuthorizeAction()
    {
        return $this->get(ComputopConstants::AUTHORIZE_ACTION);
    }

    /**
     * @return string
     */
    public function getCaptureAction()
    {
        return $this->get(ComputopConstants::CAPTURE_ACTION);
    }

    /**
     * @return string
     */
    public function getRefundAction()
    {
        return $this->get(ComputopConstants::REFUND_ACTION);
    }

    /**
     * @return string
     */
    public function getInquireAction()
    {
        return $this->get(ComputopConstants::INQUIRE_ACTION);
    }

    /**
     * @return string
     */
    public function getReverseAction()
    {
        return $this->get(ComputopConstants::REVERSE_ACTION);
    }

    /**
     * @return string
     */
    public function getIdealInitAction()
    {
        return $this->get(ComputopConstants::IDEAL_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getPaydirektInitAction()
    {
        return $this->get(ComputopConstants::PAYDIREKT_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getSofortInitAction()
    {
        return $this->get(ComputopConstants::SOFORT_INIT_ACTION);
    }

    /**
     * @return string
     */
    public function getEasyCreditStatusUrl()
    {
        return $this->get(ComputopConstants::EASY_CREDIT_STATUS_ACTION);
    }

    /**
     * @return string
     */
    public function getEasyCreditAuthorizeUrl()
    {
        return $this->get(ComputopConstants::EASY_CREDIT_AUTHORIZE_ACTION);
    }

    /**
     * @return string
     */
    public function getPayNowInitActionUrl()
    {
        return $this->get(ComputopConstants::PAY_NOW_INIT_ACTION);
    }

    /**
     * @return bool
     */
    public function isRefundShipmentPriceEnabled()
    {
        return self::COMPUTOP_REFUND_SHIPMENT_PRICE_ENABLED;
    }

    /**
     * @return array
     */
    public function getBeforeCaptureStatuses()
    {
        return [
            $this->getOmsStatusNew(),
            $this->getOmsStatusAuthorized(),
            $this->getOmsStatusAuthorizationFailed(),
            $this->getOmsStatusCancelled(),
        ];
    }

    /**
     * @return array
     */
    public function getBeforeRefundStatuses()
    {
        return [
            $this->getOmsStatusNew(),
            $this->getOmsStatusAuthorized(),
            $this->getOmsStatusCaptured(),
        ];
    }

    /**
     * @return string
     */
    public function getOmsStatusNew()
    {
        return self::OMS_STATUS_NEW;
    }

    /**
     * @return string
     */
    public function getOmsStatusInitialized()
    {
        return self::OMS_STATUS_INITIALIZED;
    }

    /**
     * @return string
     */
    public function getOmsStatusAuthorized()
    {
        return self::OMS_STATUS_AUTHORIZED;
    }

    /**
     * @return string
     */
    public function getOmsStatusAuthorizationFailed()
    {
        return self::OMS_STATUS_AUTHORIZATION_FAILED;
    }

    /**
     * @return string
     */
    public function getOmsStatusCaptured()
    {
        return self::OMS_STATUS_CAPTURED;
    }

    /**
     * @return string
     */
    public function getOmsStatusCapturingFailed()
    {
        return self::OMS_STATUS_CAPTURING_FAILED;
    }

    /**
     * @return string
     */
    public function getOmsStatusCancelled()
    {
        return self::OMS_STATUS_CANCELLED;
    }

    /**
     * @return string
     */
    public function getOmsStatusRefunded()
    {
        return self::OMS_STATUS_REFUNDED;
    }

    /**
     * @return string
     */
    public function getAuthorizeMethodName()
    {
        return self::AUTHORIZE_METHOD;
    }

    /**
     * @return string
     */
    public function getCaptureMethodName()
    {
        return self::CAPTURE_METHOD;
    }

    /**
     * @return string
     */
    public function getRefundMethodName()
    {
        return self::REFUND_METHOD;
    }

    /**
     * @return string
     */
    public function getReverseMethodName()
    {
        return self::REVERSE_METHOD;
    }

    /**
     * @return string
     */
    public function getInquireMethodName()
    {
        return self::INQUIRE_METHOD;
    }

    /**
     * @return string
     */
    public function getOmsAuthorizeEventName()
    {
        return self::COMPUTOP_OMS_EVENT_AUTHORIZE;
    }

    /**
     * @return string
     */
    public function getOmsCaptureEventName()
    {
        return self::COMPUTOP_OMS_EVENT_CAPTURE;
    }

    /**
     * @return string[]
     */
    public function getCrifGreenPaymentMethods(): array
    {
        return $this->get(ComputopConstants::CRIF_GREEN_AVAILABLE_PAYMENT_METHODS);
    }

    /**
     * @return string[]
     */
    public function getCrifYellowPaymentMethods(): array
    {
        return $this->get(ComputopConstants::CRIF_YELLOW_AVAILABLE_PAYMENT_METHODS);
    }

    /**
     * @return string[]
     */
    public function getCrifRedPaymentMethods(): array
    {
        return $this->get(ComputopConstants::CRIF_RED_AVAILABLE_PAYMENT_METHODS);
    }

    /**
     * @return bool
     */
    public function isCrifEnabled(): bool
    {
        return (bool)$this->get(ComputopConstants::CRIF_ENABLED);
    }

    /**
     * @return string
     */
    public function getEtiId(): string
    {
        return sprintf(static::ETI_ID, SharedComputopConfig::COMPUTOP_MODULE_VERSION);
    }
}
