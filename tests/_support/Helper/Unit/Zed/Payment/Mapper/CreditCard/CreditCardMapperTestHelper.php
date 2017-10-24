<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Computop\Helper\Unit\Zed\Payment\Mapper\CreditCard;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerEco\Shared\Computop\Config\ComputopFieldNameConstants;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceBridge;

class CreditCardMapperTestHelper extends Test
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createOrderTransferMock()
    {
        $orderTransferMock = $this->createPartialMock(
            OrderTransfer::class,
            ['getIdSalesOrder', 'getTotals', 'getItems']
        );

        $totalsTransferMock = $this->createPartialMock(
            TotalsTransfer::class,
            ['getGrandTotal']
        );

        $totalsTransferMock->method('getGrandTotal')
            ->willReturn('100');

        $orderTransferMock->method('getTotals')
            ->willReturn($totalsTransferMock);

        $itemsTransferMock = $this->createPartialMock(
            ItemTransfer::class,
            ['getArrayCopy']
        );

        $itemsTransferMock->method('getArrayCopy')
            ->willReturn([]);

        $orderTransferMock->method('getItems')
            ->willReturn($itemsTransferMock);

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
            ComputopFieldNameConstants::LENGTH => CreditCardMapperTestConstants::LENGTH_VALUE,
            ComputopFieldNameConstants::DATA => CreditCardMapperTestConstants::DATA_VALUE,
        ];

        $computopServiceMock = $this->createPartialMock(
            ComputopToComputopServiceBridge::class,
            ['getMacEncryptedValue', 'getEncryptedArray', 'getDescriptionValue', 'getTestModeDescriptionValue']
        );

        $computopServiceMock->method('getEncryptedArray')
            ->willReturn($encryptedArray);

        $computopServiceMock->method('getDescriptionValue')
            ->willReturn('');

        $computopServiceMock->method('getTestModeDescriptionValue')
            ->willReturn('');

        return $computopServiceMock;
    }

    /**
     * @param string $payId
     * @param string $transId
     *
     * @return \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer
     */
    public function createComputopHeaderPaymentTransfer($payId = '', $transId = '')
    {
        $headerPayment = new ComputopHeaderPaymentTransfer();
        $headerPayment->setAmount(100);
        $headerPayment->setPayId($payId);
        $headerPayment->setTransId($transId);

        return $headerPayment;
    }
}
