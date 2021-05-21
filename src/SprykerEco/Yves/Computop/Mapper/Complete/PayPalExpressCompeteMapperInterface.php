<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Complete;

use Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PayPalExpressCompeteMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer
     */
    public function createComputopPayPalExpressCompleteTransfer(QuoteTransfer $quoteTransfer): ComputopPayPalExpressCompleteTransfer;

    /**
     * @param array $computopPayPalExpressCompleteResponse
     * @param \Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer $computopPayPalExpressCompleteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalExpressCompleteTransfer
     */
    public function mapComputopPayPalExpressCompleteResponse(
        array $computopPayPalExpressCompleteResponse,
        ComputopPayPalExpressCompleteTransfer $computopPayPalExpressCompleteTransfer
    ): ComputopPayPalExpressCompleteTransfer;
}
