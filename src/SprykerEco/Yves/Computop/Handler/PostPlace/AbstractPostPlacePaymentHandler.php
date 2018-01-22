<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Handler\PostPlace;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Yves\Computop\Handler\AbstractPrePostPaymentHandler;

abstract class AbstractPostPlacePaymentHandler extends AbstractPrePostPaymentHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    abstract protected function addPaymentToQuote(QuoteTransfer $quoteTransfer, AbstractTransfer $responseTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    abstract protected function saveInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $responseArray
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer, array $responseArray)
    {
        /** @var \Generated\Shared\Transfer\ComputopSofortInitResponseTransfer $responseTransfer */
        $responseTransfer = $this->converter->getResponseTransfer($responseArray);
        $quoteTransfer = $this->addPaymentToQuote($quoteTransfer, $responseTransfer);

        $this->computopClient->logResponse($responseTransfer->getHeader());
        $this->saveInitResponse($quoteTransfer);

        return $quoteTransfer;
    }
}
