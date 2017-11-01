<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper;

use Generated\Shared\Transfer\OrderTransfer;

interface ApiMapperInterface
{
    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildRequest(OrderTransfer $orderTransfer);
}
