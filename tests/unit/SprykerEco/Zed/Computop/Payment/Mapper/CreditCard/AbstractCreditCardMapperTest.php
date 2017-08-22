<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Zed\Computop\Payment\Mapper\CreditCard;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;

abstract class AbstractCreditCardMapperTest extends Test
{

    const DATA_VALUE = 'Data';
    const LENGTH_VALUE = 10;
    const MERCHANT_ID_VALUE = 'MerchantId';

    /**
     * Return needed mapper
     *
     * @return \SprykerEco\Yves\Computop\Mapper\CreditCard\CreditCardMapperInterface
     */
    abstract protected function createMapper();

    /**
     * @return void
     */
    public function testBuildRequest()
    {
        $orderTransferMock = $this->createOrderTransferMock();

        $service = $this->createMapper();
        $mappedData = $service->buildRequest($orderTransferMock);

        $this->assertEquals(self::DATA_VALUE, $mappedData[ComputopConstants::DATA_F_N]);
        $this->assertEquals(self::LENGTH_VALUE, $mappedData[ComputopConstants::LENGTH_F_N]);
        $this->assertEquals(self::MERCHANT_ID_VALUE, $mappedData[ComputopConstants::MERCHANT_ID_F_N]);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createOrderTransferMock()
    {
        $computopPaymentTransferMock = $this->createPartialMock(
            ComputopCreditCardPaymentTransfer::class,
            ['getMerchantId']
        );

        $computopPaymentTransferMock->method('getMerchantId')
            ->willReturn(self::MERCHANT_ID_VALUE);

        $orderTransferMock = $this->createPartialMock(
            OrderTransfer::class,
            ['getComputopCreditCard']
        );

        $orderTransferMock->method('getComputopCreditCard')
            ->willReturn($computopPaymentTransferMock);

        return $orderTransferMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createComputopConfigMock()
    {
        $configMock = $this->createPartialMock(
            ComputopConfig::class,
            ['getBlowfishPass']
        );

        return $configMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createComputopServiceMock()
    {
        $encryptedArray = [
            ComputopConstants::LENGTH_F_N => self::LENGTH_VALUE,
            ComputopConstants::DATA_F_N => self::DATA_VALUE,
        ];

        $computopServiceMock = $this->createPartialMock(
            ComputopToComputopServiceBridge::class,
            ['getMacEncryptedValue', 'getEncryptedArray']
        );
        $computopServiceMock->method('getEncryptedArray')
            ->willReturn($encryptedArray);

        return $computopServiceMock;
    }

}
