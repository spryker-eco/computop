<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Oms\Command\Manager;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

interface ManagerInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface
     */
    public function getSavedComputopEntity(int $idSalesOrder): ActiveRecordInterface;
}
