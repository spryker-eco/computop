<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface;

interface ComputopPostSaveHookInterface
{
    /**
     * @param \SprykerEco\Zed\Computop\Business\Hook\Mapper\Order\AbstractMapperInterface $paymentMethod
     *
     * @return void
     */
    public function registerMapper(AbstractMapperInterface $paymentMethod);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);
}
