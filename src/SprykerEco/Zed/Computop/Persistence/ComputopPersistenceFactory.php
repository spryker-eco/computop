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
    public function createPaymentComputopQuery(): SpyPaymentComputopQuery
    {
        return SpyPaymentComputopQuery::create();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopDetailQuery
     */
    public function createPaymentComputopDetailQuery(): SpyPaymentComputopDetailQuery
    {
        return SpyPaymentComputopDetailQuery::create();
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItemQuery
     */
    public function createPaymentComputopOrderItemQuery(): SpyPaymentComputopOrderItemQuery
    {
        return SpyPaymentComputopOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSpySalesOrderItemQuery(): SpySalesOrderItemQuery
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

    /**
     * @return \SprykerEco\Zed\Computop\Persistence\Propel\Mapper\ComputopMapper
     */
    public function createComputopEntityMapper(): ComputopMapper
    {
        return new ComputopMapper();
    }
}
