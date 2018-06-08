<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Controller;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerEco\Zed\Computop\Business\ComputopFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $headerTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function logResponseAction(ComputopResponseHeaderTransfer $headerTransfer)
    {
        return $this->getFacade()->logResponseHeader($headerTransfer, $headerTransfer->getMethod());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortInitResponseAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->saveSofortInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveIdealInitResponseAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->saveIdealInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveCreditCardInitResponseAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->saveCreditCardInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayNowInitResponseAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->savePayNowInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalInitResponseAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->savePayPalInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveDirectDebitInitResponseAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->saveDirectDebitInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveEasyCreditInitResponseAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->saveEasyCreditInitResponse($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePaydirektInitResponseAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->savePaydirektInitResponse($quoteTransfer);
    }
    
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function easyCreditStatusApiCallAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->easyCreditStatusApiCall($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function isComputopPaymentExistAction(QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->isComputopPaymentExist($quoteTransfer);
    }
}
