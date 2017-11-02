<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api;

use GuzzleHttp\Psr7;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopDetail;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopOrderItem;
use SprykerEco\Service\Computop\ComputopService;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\Business\Api\ComputopBusinessApiFactory;
use SprykerEco\Zed\Computop\Business\ComputopBusinessFactory;
use SprykerEco\Zed\Computop\ComputopConfig as SprykerComputopConfig;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;
use SprykerEcoTest\Zed\Computop\Business\AbstractSetUpTest;

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
            ->setPaymentMethod(ComputopConfig::PAYMENT_METHOD_CREDIT_CARD)
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
            $paymentOrderItemEntity->setStatus(SprykerComputopConfig::OMS_STATUS_NEW);
            $paymentOrderItemEntity->save();
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject | ComputopBusinessFactory
     */
    protected function createFactory()
    {
        $apiBuilder = $this->getMockBuilder(ComputopBusinessApiFactory::class);
        $apiBuilder->setMethods([
            $this->getApiAdapterFunctionName(),
        ]);
        $apiStub = $apiBuilder->getMock();

        $apiStub->method($this->getApiAdapterFunctionName())
                ->willReturn($this->getApiAdapter());

        $builder = $this->getMockBuilder(ComputopBusinessFactory::class);
        $builder->setMethods(
            [
                'getConfig',
                'getComputopService',
                'getQueryContainer',
                'createApiFactory',
            ]
        );

        $stub = $builder->getMock();

        $stub->method('getConfig')
            ->willReturn($this->apiHelper->createConfig());

        $stub->method('getComputopService')
            ->willReturn(new ComputopService(new ComputopService()));

        $stub->method('getQueryContainer')
            ->willReturn(new ComputopQueryContainer());

        $stub->method('createApiFactory')
            ->willReturn($apiStub);

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
            ComputopApiConfig::DATA => $data,
            ComputopApiConfig::LENGTH => $length,
        ]);
        $stream = Psr7\stream_for($expectedResponse);

        return $stream;
    }
}
