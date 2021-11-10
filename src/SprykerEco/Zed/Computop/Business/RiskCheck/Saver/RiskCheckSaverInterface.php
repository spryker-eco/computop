<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\RiskCheck\Saver;

use Generated\Shared\Transfer\ComputopApiCrifResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface RiskCheckSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ComputopApiCrifResponseTransfer $responseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function save(ComputopApiCrifResponseTransfer $responseTransfer, QuoteTransfer $quoteTransfer): void;
}
