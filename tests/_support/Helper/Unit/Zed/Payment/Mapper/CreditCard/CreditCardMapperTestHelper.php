<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;

class CreditCardMapperTestHelper extends Test
{

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createOrderTransferMock()
    {
        $computopPaymentTransferMock = $this->createPartialMock(
            ComputopCreditCardPaymentTransfer::class,
            ['getMerchantId']
        );

        $computopPaymentTransferMock->method('getMerchantId')
            ->willReturn(CreditCardMapperTestConstants::MERCHANT_ID_VALUE);

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
    public function createComputopConfigMock()
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
    public function createComputopServiceMock()
    {
        $encryptedArray = [
            ComputopConstants::LENGTH_F_N => CreditCardMapperTestConstants::LENGTH_VALUE,
            ComputopConstants::DATA_F_N => CreditCardMapperTestConstants::DATA_VALUE,
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
