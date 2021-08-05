<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Orm\Zed\Computop\Persistence\SpyPaymentComputop;

interface ManagerInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop|null
     */
    public function getSavedComputopEntity(int $idSalesOrder): ?SpyPaymentComputop;
}
