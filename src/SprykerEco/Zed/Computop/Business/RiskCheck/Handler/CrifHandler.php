<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\RiskCheck\Handler;

use Generated\Shared\Transfer\ComputopCrifTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CrifHandler extends AbstractHandler
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handle(QuoteTransfer $quoteTransfer)
    {
        if (!$this->config->isCrifEnabled()) {
            return $quoteTransfer;
        }
        $responseTransfer = $this->computopApiFacade->performCrifApiCall($quoteTransfer);
        $this->saver->save($responseTransfer, $quoteTransfer);

        $computopCrifTransfer = (new ComputopCrifTransfer())
            ->fromArray($responseTransfer->toArray(), true);

        $quoteTransfer->setComputopCrif($computopCrifTransfer);

        return $quoteTransfer;
    }
}
