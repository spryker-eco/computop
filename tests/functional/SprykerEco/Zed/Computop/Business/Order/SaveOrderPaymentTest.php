<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop\Business;

use Computop\Helper\Functional\Order\OrderPaymentTestConstants;
use Functional\SprykerEco\Zed\Computop\AbstractSetUpTest;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputopQuery;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Business\ComputopFacade;

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
            $this->createCheckoutResponse()
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

        return $query->find()->getFirst();
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function createCheckoutResponse()
    {
        $checkoutResponceTransfer = new CheckoutResponseTransfer();
        $checkoutResponceTransfer->setSaveOrder($this->createSaveOrderTransfer());

        return $checkoutResponceTransfer;
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
