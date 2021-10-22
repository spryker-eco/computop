<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Controller;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $headerTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer
     */
    public function logResponseAction(ComputopApiResponseHeaderTransfer $headerTransfer): ComputopApiResponseHeaderTransfer
    {
        return $this->getFacade()->logResponseHeader($headerTransfer, $headerTransfer->getMethod());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->saveSofortInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveIdealInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->saveIdealInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveCreditCardInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->saveCreditCardInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayNowInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->savePayNowInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->savePayPalInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalExpressInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->savePayPalExpressInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalExpressCompleteResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->savePayPalExpressCompleteResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveDirectDebitInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->saveDirectDebitInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveEasyCreditInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->saveEasyCreditInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePaydirektInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->savePaydirektInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayuCeeSingleInitResponseAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->savePayuCeeSingleInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function easyCreditStatusApiCallAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->easyCreditStatusApiCall($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function isComputopPaymentExistAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->isComputopPaymentExist($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function performCrifApiCallAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->performCrifApiCall($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopNotificationTransfer
     */
    public function processNotificationAction(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): ComputopNotificationTransfer {
        return $this->getFacade()->processNotification($computopNotificationTransfer);
    }
}
