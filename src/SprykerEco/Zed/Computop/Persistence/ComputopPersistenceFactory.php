<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Orm\Zed\Computop\Persistence\SpyPaymentComputopNotificationQuery;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface getEntityManager()
 */
class ComputopPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function createPaymentComputopQuery()
    {
        return SpyPaymentComputopQuery::create();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery
     */
    public function createPaymentComputopOrderItemQuery()
    {
        return SpyPaymentComputopOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSpySalesOrderItemQuery()
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopNotificationQuery
     */
    public function createPaymentComputopNotificationQuery(): SpyPaymentComputopNotificationQuery
    {
        return SpyPaymentComputopNotificationQuery::create();
    }
}
