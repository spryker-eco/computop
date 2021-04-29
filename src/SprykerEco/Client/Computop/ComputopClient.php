<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer;
use Generated\Shared\Transfer\ComputopNotificationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerEco\Client\Computop\ComputopFactory getFactory()
 */
class ComputopClient extends AbstractClient implements ComputopClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopApiResponseHeaderTransfer $responseTransfer
     *
     * @return void
     */
    public function logResponse(ComputopApiResponseHeaderTransfer $responseTransfer)
    {
         $this->getFactory()->createZedStub()->logResponse($responseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveSofortInitResponse(QuoteTransfer $quoteTransfer)
    {
         return $this->getFactory()->createZedStub()->saveSofortInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveIdealInitResponse(QuoteTransfer $quoteTransfer)
    {
         return $this->getFactory()->createZedStub()->saveIdealInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePaydirektInitResponse(QuoteTransfer $quoteTransfer)
    {
         return $this->getFactory()->createZedStub()->savePaydirektInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveCreditCardInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->saveCreditCardInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayNowInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->savePayNowInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->savePayPalInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalExpressInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->savePayPalExpressInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function savePayPalExpressCompleteResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->savePayPalExpressCompleteResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveDirectDebitInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->saveDirectDebitInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function saveEasyCreditInitResponse(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->saveEasyCreditInitResponse($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function easyCreditStatusApiCall(QuoteTransfer $quoteTransfer)
    {
         return $this->getFactory()->createZedStub()->easyCreditStatusApiCall($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function isComputopPaymentExist(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->isComputopPaymentExist($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function performCrifApiCall(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->performCrifApiCall($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopNotificationTransfer $computopNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopNotificationTransfer
     */
    public function processNotification(
        ComputopNotificationTransfer $computopNotificationTransfer
    ): ComputopNotificationTransfer {
        return $this->getFactory()->createZedStub()->processNotification($computopNotificationTransfer);
    }
}
