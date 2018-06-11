<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business\Api\Mapper\CreditCard;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use SprykerEco\Service\Computop\ComputopService;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\ComputopToStoreBridge;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainer;

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
            ComputopApiConfig::LENGTH => CreditCardMapperTestConstants::LENGTH_VALUE,
            ComputopApiConfig::DATA => CreditCardMapperTestConstants::DATA_VALUE,
        ];

        $computopServiceMock = $this->createPartialMock(
            ComputopService::class,
            [
                'getMacEncryptedValue',
                'getEncryptedArray',
                'getDescriptionValue',
                'getTestModeDescriptionValue',
                'generateReqId',
                'generateTransId',
            ]
        );

        $computopServiceMock->method('getEncryptedArray')
            ->willReturn($encryptedArray);

        $computopServiceMock->method('getDescriptionValue')
            ->willReturn('');

        $computopServiceMock->method('getTestModeDescriptionValue')
            ->willReturn('');

        $computopServiceMock->method('generateReqId')
            ->willReturn('');

        $computopServiceMock->method('generateTransId')
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createStoreMock()
    {
        $storeMock = $this->createPartialMock(
            ComputopToStoreBridge::class,
            ['getCurrencyIsoCode']
        );

        $storeMock->method('getCurrencyIsoCode')
            ->willReturn('EUR');

        return $storeMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createQueryContainerMock()
    {
        $entity = new SpyPaymentComputop();
        $entity->setReqId('d3rf4iuhi2hiuhiufhiauhfiuaghfig23');
        $entity->setReference('DE--1');

        $queryMock = $this->createPartialMock(
            SpyPaymentComputopQuery::class,
            ['findOne']
        );

        $queryMock->method('findOne')
            ->willReturn($entity);

        $queryContainerMock = $this->createPartialMock(
            ComputopQueryContainer::class,
            ['queryPaymentByTransactionId']
        );

        $queryContainerMock->method('queryPaymentByTransactionId')
            ->willReturn($queryMock);

        return $queryContainerMock;
    }
}
