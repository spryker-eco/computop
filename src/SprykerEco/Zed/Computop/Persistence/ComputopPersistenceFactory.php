<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Persistence;

use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetailQuery;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopNotificationQuery;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerEco\Zed\Computop\Persistence\Propel\Mapper\ComputopMapper;

/**
 * @method \SprykerEco\Zed\Computop\ComputopConfig getConfig()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface getQueryContainer()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Computop\Persistence\ComputopRepositoryInterface getRepository()
 */
class ComputopPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery
     */
    public function getPaymentComputopQuery(): SpyPaymentComputopQuery
    {
        return SpyPaymentComputopQuery::create();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetailQuery
     */
    public function getPaymentComputopDetailQuery(): SpyPaymentComputopDetailQuery
    {
        return SpyPaymentComputopDetailQuery::create();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery
     */
    public function getPaymentComputopOrderItemQuery(): SpyPaymentComputopOrderItemQuery
    {
        return SpyPaymentComputopOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSpySalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopNotificationQuery
     */
    public function getPaymentComputopNotificationQuery(): SpyPaymentComputopNotificationQuery
    {
        return SpyPaymentComputopNotificationQuery::create();
    }

    /**
     * @return \SprykerEco\Zed\Computop\Persistence\Propel\Mapper\ComputopMapper
     */
    public function createComputopEntityMapper(): ComputopMapper
    {
        return new ComputopMapper();
    }
}
