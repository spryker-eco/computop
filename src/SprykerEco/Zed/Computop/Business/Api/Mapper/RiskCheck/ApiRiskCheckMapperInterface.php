<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\RiskCheck;

use Generated\Shared\Transfer\QuoteTransfer;

interface ApiRiskCheckMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildRequest(QuoteTransfer $quoteTransfer);
}
