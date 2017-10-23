<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Order;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface AbstractMapperInterface
{
    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    public function updateComputopPaymentTransfer(TransferInterface $quoteTransfer, TransferInterface $computopPaymentTransfer);
}
