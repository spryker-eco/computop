<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop\Zed;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerEco\Client\Computop\ComputopConfig;

class ComputopStub extends ZedRequestStub implements ComputopStubInterface
{
    /**
     * @var \SprykerEco\Client\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedStub
     * @param \SprykerEco\Client\Computop\ComputopConfig $config
     */
    public function __construct(
        ZedRequestClientInterface $zedStub,
        ComputopConfig $config
    ) {
        parent::__construct($zedStub);

        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $responseTransfer
     *
     * @return void
     */
    public function logResponse(ComputopApiResponseHeaderTransfer $responseTransfer): void
    {
        $this->zedStub->call('/computop/gateway/log-response', $responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-sofort-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveIdealInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-ideal-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @uses \SprykerEco\Zed\Computop\Communication\Controller\GatewayController::savePayuCeeSingleInitResponseAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayuCeeSingleInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-payu-cee-single-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @uses \SprykerEco\Zed\Computop\Communication\Controller\GatewayController::savePaydirektInitResponseAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePaydirektInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-paydirekt-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveCreditCardInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-credit-card-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayNowInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-pay-now-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-pay-pal-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @uses \SprykerEco\Zed\Computop\Communication\Controller\GatewayController::savePayPalExpressInitResponseAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalExpressInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-pay-pal-express-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @uses \SprykerEco\Zed\Computop\Communication\Controller\GatewayController::savePayPalExpressCompleteResponseAction()
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalExpressCompleteResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-pay-pal-express-complete-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveDirectDebitInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-direct-debit-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveEasyCreditInitResponse(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/save-easy-credit-init-response', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function easyCreditStatusApiCall(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/easy-credit-status-api-call', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function isComputopPaymentExist(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/is-computop-payment-exist', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function performCrifApiCall(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$this->config->isCrifEnabled()) {
            return $quoteTransfer;
        }

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedStub->call('/computop/gateway/perform-crif-api-call', $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopNotificationTransfer
     */
    public function processNotification(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): ComputopNotificationTransfer {
        /** @var \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer */
        $computopNotificationTransfer = $this->zedStub->call(
            '/computop/gateway/process-notification',
            $computopNotificationTransfer,
        );

        return $computopNotificationTransfer;
    }
}
