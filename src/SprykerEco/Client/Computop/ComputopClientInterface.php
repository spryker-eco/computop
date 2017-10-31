<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Computop;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \SprykerEco\Client\Computop\ComputopFactory getFactory()
 */
interface ComputopClientInterface
{
    /**
     * Specification:
     * - Save response log to DB
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $responseTransfer
     *
     * @return void
     */
    public function logResponse(ComputopResponseHeaderTransfer $responseTransfer);

    /**
     * Specification:
     * - Save Sofort response to DB
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSofortResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Save Paydirekt response to DB
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function savePaydirektResponse(QuoteTransfer $quoteTransfer);
}
