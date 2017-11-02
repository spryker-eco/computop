<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Communication\Hook;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\Communication\Hook\Mapper\Order\OrderMapperInterface;

interface ComputopPostSaveHookInterface
{
    /**
     * @param \SprykerEco\Zed\Computop\Communication\Hook\Mapper\Order\OrderMapperInterface $paymentMethod
     *
     * @return void
     */
    public function registerMapper(OrderMapperInterface $paymentMethod);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);
}