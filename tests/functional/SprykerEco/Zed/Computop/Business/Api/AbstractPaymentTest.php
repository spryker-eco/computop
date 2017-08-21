<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business\Api;

use GuzzleHttp\Psr7;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use SprykerEco\Service\Computop\ComputopService;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

abstract class AbstractPaymentTest extends AbstractFactoryPaymentTest
{

    /**
     * @return string
     */
    abstract protected function getPayIdValue();

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    abstract protected function getApiAdapter();

    /**
     * @return string
     */
    abstract protected function getApiAdapterFunctionName();

    /**
     * Set up DB data
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpComputop();
    }

    /**
     * @return void
     */
    protected function setUpComputop()
    {
        $this->createComputopPaymentEntity();
    }

    /**
     * @return void
     */
    protected function createComputopPaymentEntity()
    {
        $computopPaymentEntity = (new SpyPaymentComputop())
            ->setClientIp('0.0.0.0')
            ->setPaymentMethod(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD)
            ->setReference($this->orderEntity->getOrderReference())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setPayId($this->getPayIdValue());
        $computopPaymentEntity->save();

        $paymentDetailEntity = new SpyPaymentComputopDetail();
        $paymentDetailEntity->setIdPaymentComputop($computopPaymentEntity->getIdPaymentComputop());
        $paymentDetailEntity->save();

        $orderItemTransfers = $this->orderEntity->getItems();

        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentComputopOrderItem();
            $paymentOrderItemEntity
                ->setFkPaymentComputop($computopPaymentEntity->getIdPaymentComputop())
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->setStatus(ComputopConstants::COMPUTOP_OMS_STATUS_NEW);
            $paymentOrderItemEntity->save();
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | ComputopBusinessFactory
     */
    protected function createFactory()
    {
        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);

        $builder->setMethods(
            [
                'getConfig',
                'getComputopService',
                $this->getApiAdapterFunctionName(),
                'getQueryContainer',
            ]
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->createConfig());

        $stub->method('getComputopService')
            ->willReturn(new ComputopToComputopServiceBridge(new ComputopService()));

        $stub->method($this->getApiAdapterFunctionName())
            ->willReturn($this->getApiAdapter());

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        return $stub;
    }

    /**
     * @param string $data
     * @param string $len
     *
     * @return Psr7\Stream
     */
    protected function getStream($data, $len)
    {
        $expectedResponse = http_build_query([
            ComputopConstants::DATA_F_N => $data,
            ComputopConstants::LEN_F_N => $len,
        ]);
        $stream = Psr7\stream_for($expectedResponse);

        return $stream;
    }

}
