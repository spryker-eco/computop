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
     * - Saves response log to DB
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
     * - Saves Sofort response to DB
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSofortInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Saves Ideal response to DB
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveIdealInitResponse(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Saves Paydirekt response to DB
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function savePaydirektInitResponse(QuoteTransfer $quoteTransfer);
    
    /**
     * Specification:
     * - Makes Easy Credit Status API call
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function easyCreditStatusApiCall(QuoteTransfer $quoteTransfer);
}
