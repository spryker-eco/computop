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
    /**
     * @var string
     */
    public const OMS_STATUS_NEW = 'new';
    /**
     * @var string
     */
    public const OMS_STATUS_INITIALIZED = 'init';
    /**
     * @var string
     */
    public const OMS_STATUS_AUTHORIZED = 'authorized';
    /**
     * @var string
     */
    public const OMS_STATUS_AUTHORIZATION_FAILED = 'authorization failed';
    /**
     * @var string
     */
    public const OMS_STATUS_CAPTURED = 'captured';
    /**
     * @var string
     */
    public const OMS_STATUS_CAPTURING_FAILED = 'capture failed';
    /**
     * @var string
     */
    public const OMS_STATUS_CANCELLED = 'cancelled';
    /**
     * @var string
     */
    public const OMS_STATUS_REFUNDED = 'refunded';

    /**
     * @var string
     */
    public const AUTHORIZE_METHOD = 'AUTHORIZE';
    /**
     * @var string
     */
    public const CAPTURE_METHOD = 'CAPTURE';
    /**
     * @var string
     */
    public const REVERSE_METHOD = 'REVERSE';
    /**
     * @var string
     */
    public const INQUIRE_METHOD = 'INQUIRE';
    /**
     * @var string
     */
    public const REFUND_METHOD = 'REFUND';

    //Events
    /**
     * @var string
     */
    public const COMPUTOP_OMS_EVENT_CAPTURE = 'capture';
    /**
     * @var string
     */
    public const COMPUTOP_OMS_EVENT_AUTHORIZE = 'authorize';

    /**
     * Refund with shipment price
     *
     * @var bool
     */
    public const COMPUTOP_REFUND_SHIPMENT_PRICE_ENABLED = true;

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->get(ComputopApiConstants::MERCHANT_ID);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBlowfishPass()
    {
        return $this->get(ComputopApiConstants::BLOWFISH_PASSWORD);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAuthorizeAction()
    {
        return $this->get(ComputopConstants::AUTHORIZE_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCaptureAction()
    {
        return $this->get(ComputopConstants::CAPTURE_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getRefundAction()
    {
        return $this->get(ComputopConstants::REFUND_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getInquireAction()
    {
        return $this->get(ComputopConstants::INQUIRE_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getReverseAction()
    {
        return $this->get(ComputopConstants::REVERSE_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getIdealInitAction()
    {
        return $this->get(ComputopConstants::IDEAL_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPaydirektInitAction()
    {
        return $this->get(ComputopConstants::PAYDIREKT_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSofortInitAction()
    {
        return $this->get(ComputopConstants::SOFORT_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEasyCreditStatusUrl()
    {
        return $this->get(ComputopConstants::EASY_CREDIT_STATUS_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEasyCreditAuthorizeUrl()
    {
        return $this->get(ComputopConstants::EASY_CREDIT_AUTHORIZE_ACTION);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPayNowInitActionUrl()
    {
        return $this->get(ComputopConstants::PAY_NOW_INIT_ACTION);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isRefundShipmentPriceEnabled()
    {
        return static::COMPUTOP_REFUND_SHIPMENT_PRICE_ENABLED;
    }

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @return string
     */
    public function getOmsStatusNew()
    {
        return static::OMS_STATUS_NEW;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusInitialized()
    {
        return static::OMS_STATUS_INITIALIZED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusAuthorized()
    {
        return static::OMS_STATUS_AUTHORIZED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusAuthorizationFailed()
    {
        return static::OMS_STATUS_AUTHORIZATION_FAILED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCaptured()
    {
        return static::OMS_STATUS_CAPTURED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCapturingFailed()
    {
        return static::OMS_STATUS_CAPTURING_FAILED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusCancelled()
    {
        return static::OMS_STATUS_CANCELLED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsStatusRefunded()
    {
        return static::OMS_STATUS_REFUNDED;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAuthorizeMethodName()
    {
        return static::AUTHORIZE_METHOD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCaptureMethodName()
    {
        return static::CAPTURE_METHOD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getRefundMethodName()
    {
        return static::REFUND_METHOD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getReverseMethodName()
    {
        return static::REVERSE_METHOD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getInquireMethodName()
    {
        return static::INQUIRE_METHOD;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsAuthorizeEventName()
    {
        return static::COMPUTOP_OMS_EVENT_AUTHORIZE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOmsCaptureEventName()
    {
        return static::COMPUTOP_OMS_EVENT_CAPTURE;
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getCrifGreenPaymentMethods(): array
    {
        return $this->get(ComputopConstants::CRIF_GREEN_AVAILABLE_PAYMENT_METHODS);
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getCrifYellowPaymentMethods(): array
    {
        return $this->get(ComputopConstants::CRIF_YELLOW_AVAILABLE_PAYMENT_METHODS);
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getCrifRedPaymentMethods(): array
    {
        return $this->get(ComputopConstants::CRIF_RED_AVAILABLE_PAYMENT_METHODS);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isCrifEnabled(): bool
    {
        return (bool)$this->get(ComputopConstants::CRIF_ENABLED);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getEtiId(): string
    {
        return SharedComputopConfig::COMPUTOP_MODULE_VERSION;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getIdealIssuerId(): string
    {
        return $this->get(ComputopConstants::IDEAL_ISSUER_ID);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getDefaultShipmentMethodId(): int
    {
        return (int)$this->get(ComputopConstants::PAYPAL_EXPRESS_DEFAULT_SHIPMENT_METHOD_ID);
    }
}
