<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerEco\Client\Computop\ComputopFactory getFactory()
 */
class ComputopClient extends AbstractClient implements ComputopClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $responseTransfer
     *
     * @return void
     */
    public function logResponse(ComputopResponseHeaderTransfer $responseTransfer)
    {
         $this->getFactory()->createZedStub()->logResponse($responseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSofortResponse(QuoteTransfer $quoteTransfer)
    {
         $this->getFactory()->createZedStub()->saveSofortResponse($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveIdealResponse(QuoteTransfer $quoteTransfer)
    {
         $this->getFactory()->createZedStub()->saveIdealResponse($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function savePaydirektResponse(QuoteTransfer $quoteTransfer)
    {
         $this->getFactory()->createZedStub()->savePaydirektResponse($quoteTransfer);
    }
}
