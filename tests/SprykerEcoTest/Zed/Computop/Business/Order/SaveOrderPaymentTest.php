<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Business;

use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\ComputopFacade;
use SprykerEcoTest\Zed\Computop\Order\OrderPaymentTestConstants;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Computop
 * @group Business
 * @group SaveOrderPaymentTest
 */
class SaveOrderPaymentTest extends AbstractSetUpTest
{
    /**
     * @return void
     */
    public function testSaveOrderPaymentSuccess()
    {
        $service = new ComputopFacade();
        $service->setFactory($this->orderHelper->createFactory());
        $service->saveOrderPayment(
            $this->orderHelper->createQuoteTransfer(),
            $this->createSaveOrderTransfer(),
        );

        $computopSavedData = $this->getComputopSavedData();

        $this->assertSame(OrderPaymentTestConstants::CLIENT_IP_VALUE, $computopSavedData->getClientIp());
        $this->assertSame(OrderPaymentTestConstants::PAY_ID_VALUE, $computopSavedData->getPayId());
        $this->assertSame(OrderPaymentTestConstants::TRANS_ID_VALUE, $computopSavedData->getTransId());
        $this->assertSame(ComputopConfig::PAYMENT_METHOD_CREDIT_CARD, $computopSavedData->getPaymentMethod());
        $this->assertSame($this->helper->orderEntity->getIdSalesOrder(), $computopSavedData->getFkSalesOrder());
        $this->assertSame($this->helper->orderEntity->getOrderReference(), $computopSavedData->getReference());
    }

    /**
     * @return \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected function getComputopSavedData()
    {
        $query = new SpyPaymentComputopQuery();

        return $query->find()->getLast();
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createSaveOrderTransfer()
    {
        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->setIdSalesOrder($this->helper->orderEntity->getIdSalesOrder());
        $saveOrderTransfer->setOrderReference($this->helper->orderEntity->getOrderReference());

        return $saveOrderTransfer;
    }
}
