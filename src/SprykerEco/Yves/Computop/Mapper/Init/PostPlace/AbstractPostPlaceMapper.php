<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;

abstract class AbstractPostPlaceMapper extends AbstractMapper
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createComputopPaymentTransfer(TransferInterface $quoteTransfer)
    {
        return parent::createComputopPaymentTransfer($quoteTransfer);
    }
}
