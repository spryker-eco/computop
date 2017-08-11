<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapperInterface;

interface CreditCardMapperInterface extends AbstractMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildRequest(OrderTransfer $orderTransfer);

}
