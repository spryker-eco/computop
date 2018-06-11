<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Dependency\Facade;

use Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ComputopToComputopApiFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ComputopApiHeaderPaymentTransfer $headerPaymentTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function performEasyCreditStatusRequest(
        QuoteTransfer $quoteTransfer,
        ComputopApiHeaderPaymentTransfer $headerPaymentTransfer
    );
}
