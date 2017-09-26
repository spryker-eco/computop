<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business\Api;

use Functional\SprykerEco\Zed\Computop\AbstractSetUpTest;
use GuzzleHttp\Psr7;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use SprykerEco\Service\Computop\ComputopService;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

abstract class AbstractPaymentTest extends AbstractSetUpTest
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
            ->setReference($this->helper->orderEntity->getOrderReference())
            ->setFkSalesOrder($this->helper->orderEntity->getIdSalesOrder())
            ->setPayId($this->getPayIdValue());
        $computopPaymentEntity->save();

        $paymentDetailEntity = new SpyPaymentComputopDetail();
        $paymentDetailEntity->setIdPaymentComputop($computopPaymentEntity->getIdPaymentComputop());
        $paymentDetailEntity->save();

        $orderItemTransfers = $this->helper->orderEntity->getItems();

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
                'getQueryContainer',
                'getPaymentMethod',
                $this->getApiAdapterFunctionName(),
            ]
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->apiHelper->createConfig());

        $stub->method('getComputopService')
            ->willReturn(new ComputopToComputopServiceBridge(new ComputopService()));

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        $stub->method('getPaymentMethod')
            ->willReturn(ComputopConstants::PAYMENT_METHOD_CREDIT_CARD);

        $stub->method($this->getApiAdapterFunctionName())
            ->willReturn($this->getApiAdapter());

        return $stub;
    }

    /**
     * @param string $data
     * @param int $length
     *
     * @return Psr7\Stream
     */
    protected function getStream($data, $length)
    {
        $expectedResponse = http_build_query([
            ComputopConstants::DATA_F_N => $data,
            ComputopConstants::LENGTH_F_N => $length,
        ]);
        $stream = Psr7\stream_for($expectedResponse);

        return $stream;
    }

}
